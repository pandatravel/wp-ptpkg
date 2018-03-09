// Mixins
import { validationMixin } from 'vuelidate'
import { required, minLength, maxLength, email, sameAs, between } from 'vuelidate/lib/validators'
import { ageRange, creditCard, creditCardExpiration, creditCardCvv } from '../validators'
import errors from '../mixins/errors'
import form from '../mixins/form'
import countries from '../mixins/countries'
import states from '../mixins/states'
import creditCards from '../mixins/creditCards'
import creditCardMask from '../mixins/creditCardMask'
// Components
import Room from '../components/Room.vue';
import TravelerExtended from '../components/TravelerExtended.vue';
import OrderInfo from '../components/OrderInfo.vue';

const touchMap = new WeakMap()

Vue.component('booking-form', {
    mixins: [
        validationMixin,
        errors,
        form,
        countries,
        states,
        creditCards,
        creditCardMask,
    ],

    components: {
        Room,
        TravelerExtended,
        OrderInfo,
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
                premium: '',
                deposit: '',
                total: '',
                name: 'Ammon Casey',
                card_number: '378282246310005',
                card_expiration: '2020-08',
                card_cvv: '1234',
                address: '1017 Kapahulu Ave.',
                address2: '',
                city: 'Honolulu',
                country: 'US',
                state: 'HI',
                zip: '96816',
                phone: '8082033908',
                fax: '8087383805',
                email: 'ammonkc@gmail.com',
                email_confirm: 'ammonkc@gmail.com',
                requests: '',
                purchased: false,
                insurance: false,
                subscribe: true,
                agree_terms: true,

                rooms: [],
            }),

            step: 4,
            room_max: 3,
            isDeposit: false,
            full_terms: false,
            exp_menu: false,
            exp_min: moment().subtract(1, 'month').format('YYYY-MM'),
            exp_max: moment().add(8, 'YEAR').format('YYYY-MM'),
            submiting: false,
            success: false,

            order: {},
            package: window._ptpkgAPIDataPreload.data,
        }
    },

    validations () {
        switch (this.step) {
            case 1:
                return {
                    form: {
                        rooms: {
                            required,
                            minLength: minLength(1),
                            $each: {
                                travelers: {
                                    required,
                                    minLength: minLength(1),
                                    maxLength: maxLength(this.room_max),
                                    $each: {
                                        first_name: {required},
                                        last_name: {required},
                                        birthdate: {
                                            required,
                                            ageRange: ageRange(this.package.child_min_age, this.package.child_max_age),
                                        },
                                        gender: {required},
                                    }
                                }
                            }
                        }
                    }
                }
                break
            case 2:
                return {
                    form: {
                        rooms: {
                            required,
                            minLength: minLength(1),
                            $each: {
                                travelers: {
                                    required,
                                    minLength: minLength(1),
                                    maxLength: maxLength(this.room_max),
                                    $each: {
                                        country: {required},
                                        state: {required},
                                    }
                                }
                            }
                        }
                    }
                }
                break
            case 3:
                return {
                    form: {
                        address: {required},
                        city: {required},
                        state: {required},
                        country: {required},
                        zip: {required},
                        phone: {required},
                        email: {
                            required,
                            email,
                            sameAs: sameAs('email_confirm'),
                        },
                        email_confirm: {
                            required,
                            email,
                            sameAs: sameAs('email'),
                        },
                        name: {required},
                        card_number: {
                            required,
                            creditCard,
                        },
                        card_expiration: {
                            required,
                            creditCardExpiration,
                        },
                        card_cvv: {
                            required,
                            creditCardCvv,
                        },
                        agree_terms: {required},
                        rooms: {
                            required,
                            minLength: minLength(1),
                            $each: {
                                travelers: {
                                    required,
                                    minLength: minLength(1),
                                    maxLength: maxLength(this.room_max),
                                    $each: {
                                        first_name: {required},
                                        last_name: {required},
                                        birthdate: {required},
                                        gender: {required},
                                        country: {required},
                                        state: {required},
                                    }
                                }
                            }
                        }
                    }
                }
                break
            default:
                return {
                    form: {
                        address: {required},
                        city: {required},
                        state: {required},
                        country: {required},
                        zip: {required},
                        phone: {required},
                        email: {
                            required,
                            email,
                            sameAs: sameAs('email_confirm'),
                        },
                        email_confirm: {
                            required,
                            email,
                            sameAs: sameAs('email'),
                        },
                        name: {required},
                        card_number: {
                            required,
                            creditCard,
                        },
                        card_expiration: {
                            required,
                            creditCardExpiration,
                        },
                        card_cvv: {
                            required,
                            creditCardCvv,
                        },
                        agree_terms: {required},
                        rooms: {
                            required,
                            minLength: minLength(1),
                            $each: {
                                travelers: {
                                    required,
                                    minLength: minLength(1),
                                    maxLength: maxLength(this.room_max),
                                    $each: {
                                        first_name: {required},
                                        last_name: {required},
                                        birthdate: {required},
                                        gender: {required},
                                        country: {required},
                                        state: {required},
                                    }
                                }
                            }
                        }
                    }
                }
        }
    },

    created() {
        this.form.tour_id = this.package.id;
        this.room_max = this.package.room_max;

        this.addRoom()
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
            return this.rateTier.id
        },
        subTotal() {
            return Number(this.package.rates.reduce(this.rateReducer, 0));
        },
        total() {
            let total = (this.form.insurance ? Number(this.subTotal + this.insurance) : this.subTotal)
            if (! this.isDeposit) {
                this.form.deposit = ''
                this.form.total = total
                this.form.purchased = true
            }
            return total
        },
        deposit() {
            let deposit = Number(this.package.deposit * this.totalTravelers)
            if (this.form.insurance) {
                deposit = deposit + this.insurance
            }
            if (this.isDeposit) {
                this.form.deposit = deposit
                this.form.total = ''
                this.form.purchased = false
            }
            return deposit
        },
        perPersonRate() {
            if (this.totalTravelers == 0) {
                return 0;
            }
            return this.subTotal / this.totalTravelers;
        },
        balanceTotal() {
            return this.isDeposit ? Number(this.total - this.deposit) : this.total;
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
            let insurance = 0;
             if (this.form.insurance) {
                insurance = this.premiumPrice * this.totalTravelers;
                this.form.premium = insurance
             }
            return insurance
        },
        roomsAvailable() {
            return this.package.room_block - this.package.rooms_count - this.form.rooms.length
        },
        packagesAvailable() {
            return this.package.package_block - this.package.packages_count
        },
        balanceDuePeriod() {
            let balanceDue = moment(this.package.balance_at, 'MM/DD/YYYY')
            let travelStart = moment(this.package.travel_start_at, 'MM/DD/YYYY')
            return travelStart.diff(balanceDue, 'days')
        },
    },

    watch: {
        rateId(val) {
            this.form.rate_id = val
        },
        exp_menu (val) {
            val && this.$nextTick(() => (this.$refs.exp_picker.activePicker = 'YEAR'))
        },
    },

    mounted() {
        // console.log(this.$v.form.rooms.$each[0].travelers.$each[0].birthdate)
    },

    methods: {
        onSubmit() {
            this.$v.$touch()
            if (this.$v.$invalid) {
                this.$notify({ group: 'package', type: 'error', title: 'Error!', text: 'The form contains invalid fields.'});
                return false
            } else {
                this.submiting = true

                this.form.post(this.endpoint)
                         .then(response => {
                            this.submiting = false
                            this.success = true
                            this.order = response.data
                         })
                         .catch(errors => {
                            this.submiting = false
                            this.$notify({ group: 'package', type: 'error', title: 'Error!', text: 'There was a problem submiting your order.', data: errors});
                            console.log(errors)
                        });
            }
        },
        clear () {
            this.$v.$reset()
            this.form.reset()
        },
        addRoom() {
            if (this.roomsAvailable === 0) {
                this.$notify({ group: 'package', type: 'warn', title: 'Tour Is Full!', text: 'All rooms on this tour are full.'});
                return false
            }
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
                        first_name:'Ammon',
                        middle_name:'Kaohiai',
                        last_name:'Casey',
                        birthdate: '1979-05-31',
                        gender: 'Male',
                        adult: adult,
                        ffp: '327709212',
                        seat_preference: 'Window Seat',
                        country: 'US',
                        state: 'HI',
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
        nextStep(step) {
            this.$v.$touch()
            if (! this.$v.$invalid) {
                this.step = step
            }
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
        exp_save(date) {
            this.$refs.exp_menu.save(date)
        },
        delayTouch($v) {
            $v.$reset()
            if (touchMap.has($v)) {
                clearTimeout(touchMap.get($v))
            }
            touchMap.set($v, setTimeout($v.$touch, 1000))
        }
    },

    filters: {
        age(value) {
            return moment().diff(value, 'years')
        },
        dateFormat(value) {
            return moment(value).format('MM/DD/YYYY')
        },
    }

});
