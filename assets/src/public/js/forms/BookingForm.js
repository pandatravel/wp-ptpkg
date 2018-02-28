// Mixins
import { validationMixin } from 'vuelidate'
import { required, minLength, maxLength, email } from 'vuelidate/lib/validators'
import errors from '../mixins/errors'
import form from '../mixins/form'
// Components
import room from '../components/Room.vue';

Vue.component('booking-form', {
    mixins: [
        validationMixin,
        errors,
        form,
    ],

    components: {
        room,
    },

    props: {
        'endpoint': {
            type: String,
            default: function() {
                return 'packages';
            }
        }
    },

    data() {
        return {
            form: new Form({
                tour_id: '',
                rate_id: '',
                insurance: false,
                subscribe: false,
                agree_terms: '',

                rooms: [],
            }),
            step:1,
            room_max: 3,
            submiting: false,

            package: window._ptpkgAPIDataPreload.data,
        }
    },

    validations: {
        form: {
            agree_terms: { required },
            rooms: {
                required,
                minLength: minLength(1),
                $each: {
                    travelers: {
                        required,
                        minLength: minLength(1),
                        $each: {
                            first_name: {required},
                            last_name: {required},
                            birthdate: {required},
                            gender: {required},
                        }
                    }
                }
            }
        }
    },

    created() {
        this.form.tour_id = this.package.id;
        this.room_max = this.package.room_max;

        this.addRoom();

        this.form.rate_id = this.rateId;
    },

    computed: {
        adults() {
            return this.form.rooms.reduce(function(adults, room) {
                return adults + room.travelers.reduce(function(adults, traveler) {
                    return (traveler.adult ? adults + 1 : adults);
                }, 0);
            }, 0);
        },
        children() {
            return this.form.rooms.reduce(function(children, room) {
                return children + room.travelers.reduce(function(children, traveler) {
                    return (! traveler.adult ? children + 1 : children);
                }, 0);
            }, 0);
        },
        totalTravelers() {
            return this.adults + this.children;
        },
        maxTravelers() {
            return this.package.rates.reduce(function(max, rate) {
                var total = rate.adult + rate.child;
                return (total > max ? total : max);
            }, 0);
        },
        rateTier() {
            if (this.totalTravelers == 0) {
                return [];
            }
            var rate = this.package.rates.filter(this.rateFilter(this.adults, this.children));
            return rate[0];
        },
        rateId() {
            return this.rateTier.id;
        },
        subTotal() {
            return Number(this.package.rates.reduce(this.rateReducer, 0));
        },
        total() {
            return this.subTotal + this.insurance;
        },
        perPersonRate() {
            if (this.totalTravelers == 0) {
                return 0;
            }
            return this.subTotal / this.totalTravelers;
        },
        premium() {
            if (this.totalTravelers == 0) {
                return [];
            }
            return this.package.insurance.premiums.filter(this.premiumFilter(this.perPersonRate));
        },
        premiumPrice() {
            return Number(this.package.insurance.premiums.reduce(this.premiumReducer, 0));
        },
        insurance() {
            return this.premiumPrice * this.totalTravelers;
        },
    },

    methods: {
        onSubmit() {
            this.$v.$touch()
            if (this.$v.$invalid) {
                this.$notify({ group: 'package', type: 'error', title: 'Error!', text: 'The form contains invalid fields.'});
                return false;
            } else {
                this.submiting = true;

                this.form.post(this.endpoint)
                         .then(data => {
                            this.submiting = false;
                            console.log(data)
                         })
                         .catch(errors => {
                            this.submiting = false;
                            this.$notify({ group: 'package', type: 'error', title: 'Error!', text: 'There was a problem submiting your order.', data: errors});
                            console.log(errors)
                        });
            }
        },
        onSuccess(data) {
            this.submiting = false;
        },
        onFail(errors) {
            this.submiting = false;
            // var bag = this.$validator.errors;
            // Object.keys(errors).map(function(key) {
            //     var splitted = key.split('.', 2);
            //     // we assume that first dot divides column and locale (TODO maybe refactor this and make it more general)
            //     if (splitted.length > 1) {
            //         bag.add(splitted[0]+'_'+splitted[1], errors[key][0]);
            //     } else {
            //         bag.add(key, errors[key][0]);
            //     }
            // });
        },
        clear () {
            this.$v.$reset()
            this.form.reset()
        },
        addRoom() {
            var newRoom = this.form.rooms.push({travelers:[]}) -1;
            this.addAdult(newRoom);
        },
        removeRoom(roomIndex) {
            this.form.rooms.splice(roomIndex, 1)
        },
        addAdult(roomIndex) {
            this.addTraveler(roomIndex, true);
        },
        addChild(roomIndex) {
            this.addTraveler(roomIndex, false);
        },
        addTraveler(roomIndex, adult = true) {
            if (this.totalTravelers < this.maxTravelers) {
                if (this.hasVacancy(roomIndex)) {
                    this.form.rooms[roomIndex].travelers.push({
                        first_name:'',
                        middle_name:'',
                        last_name:'',
                        birthdate: null,
                        gender: '',
                        adult: adult,
                        ffp: '',
                        seat_preference: '',
                        country: '',
                        state: '',
                    });
                }
            }
        },
        removeTraveler(index) {
            this.form.rooms[index.room].travelers.splice(index.traveler, 1);
        },
        getRoomCount(roomIndex) {
            return this.form.rooms[roomIndex].travelers.length;
        },
        hasVacancy(roomIndex) {
            return this.getRoomCount(roomIndex) < this.room_max;
        },
        rateFilter(adults, children) {
            return function(rate) {
                return rate.adult == adults && rate.child == children;
            }
        },
        premiumFilter(perPersonRate) {
            return function(premium) {
                return perPersonRate >= premium.range_start && perPersonRate <=premium.range_end;
            }
        },
        rateReducer(price, rate) {
            if (rate.adult == this.adults && rate.child == this.children) {
                price = rate.price;
            }
            return price;
        },
        premiumReducer(price, premium) {
            if (this.perPersonRate >= premium.range_start && this.perPersonRate <= premium.range_end) {
                price = premium.price;
            }
            return price;
        },
    }

});
