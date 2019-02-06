import valid from 'card-validator'
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
// Fontawesome
import { library } from '@fortawesome/fontawesome-svg-core'
import { faCcAmex, faCcVisa, faCcMastercard, faCcDiscover, faCcJcb, faCcDinersClub } from '@fortawesome/free-brands-svg-icons'
import { faAddressCard, faCreditCard, faPhone, faFax, faEnvelope, faMapMarkerAlt, faAsterisk, faCheck } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
library.add(faCcAmex, faCcVisa, faCcMastercard, faCcDiscover, faCcJcb, faCcDinersClub, faAddressCard, faCreditCard, faPhone, faFax, faEnvelope, faMapMarkerAlt, faAsterisk, faCheck)


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
        FontAwesomeIcon,
    },

    props: {
        'endpoint': {
            type: String,
            default: function() {
                return 'packages';
            }
        },
        'id': {
            type: Number,
            default: function() {
                return null;
            }
        }
    },

    data() {
        return {
            form: new Form({
                tour_id: '',
                rate_id: '',
                discount_id: '',
                premium: '',
                deposit: '',
                balance: '',
                amount: '',
                name: '',
                card_number: '',
                card_expiration: '',
                card_cvv: '',
                address: '',
                address2: '',
                city: '',
                country: '',
                state: '',
                zip: '',
                phone: '',
                fax: '',
                email: '',
                email_confirm: '',
                requests: '',
                status: false,
                discounted: false,
                subscribe: false,
                agree_terms: false,

                rooms: [],

                method: '',
                code: '',
                description: '',

            }),

            step: 1,
            room_max: 3,
            isDeposit: false,

            full_terms: false,
            exp_menu: false,
            exp_min: moment().subtract(1, 'month').format('YYYY-MM'),
            exp_max: moment().add(8, 'YEAR').format('YYYY-MM'),
            submiting: false,
            success: false,

            order: {},
            bookable: '',
            package: window._ptpkgAPIDataPreload.data,
            wp_error: '',
            shared: store.state,
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
        if (this.package) {
            this.bookable = this.package.is_bookable
            this.form.tour_id = this.package.id
            this.form.code = this.package.code
            this.form.description = this.package.name
            this.form.method = 'credit card'
            this.form.discounted = !this.package.discount ? false : true
            if (this.form.discounted) {
                this.form.discount_id = this.package.discount.id
            }
            this.room_max = this.package.room_max

            this.addRoom(null, 2)
        } else {
            this.bookable = false
            store.setStatus(window._ptpkgAPIDataPreload.error)
        }
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
        roomMax() {
            return this.package.rates.reduce(function(max, rate) {
                var total = rate.adult + rate.child;
                return (total > max ? total : max);
            }, 0);
        },
        amount() {
            let amount = this.sub_total

            if (this.isDeposit  && this.package.allow_deposit) {
                amount = this.deposit
            } else {
                if (this.form.discounted) {
                    amount -= this.discount
                }
            }

            amount += this.premium

            return amount
        },
        sub_total() {
            return this.form.rooms.reduce((price, room) => Number(price) + Number(room.rate.price), 0)
        },
        total() {
            let total = this.sub_total
            total += this.premium
            if (this.form.discounted) {
                total -= this.discount
            }
            return total
        },
        discount() {
            return Number(this.package.discount.amount * this.totalTravelers)
        },
        balance() {
            return Number(this.total - this.amount)
        },
        deposit() {
            return Number(this.package.deposit * this.totalTravelers)
        },
        premium() {
            return Number(this.form.rooms.reduce((price, room) => Number(price) + Number(room.premium.price * room.travelers.filter(traveler => traveler.insurance == true).length), 0))
        },
        deposit_total() {
            let deposit = this.deposit
            if (this.premium != 0) {
                deposit += this.premium
            }
            return deposit
        },
        perPersonRate() {
            if (this.totalTravelers == 0) {
                return 0;
            }
            return this.sub_total / this.totalTravelers;
        },
        premium_tier() {
            if (this.totalTravelers == 0) {
                return [];
            }
            return this.package.insurance.premiums.filter(this.premiumFilter(this.perPersonRate));
        },
        premium_price() {
            return Number(this.package.insurance.premiums.reduce(this.premiumReducer, 0));
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
        statusMessage() {
            let message = ''
            if (! this.bookable) {
                if (this.shared.status) {
                    message = this.shared.status.message
                }
            }
            return message
        },
        cardIcon() {
            let number = valid.number(this.form.card_number)
            if (number.card) {
                let type = number.card.type
                let card = this.creditCards.filter(function(item) {
                    return item.card == type
                })
                if (card.length == 0) {
                    return {'card':'credit-card', 'fa': 'fas'}
                }
                return card[0].icon
            }
            return {'card':'credit-card', 'fa': 'fas'}
        },
        cardNiceType() {
            let number = valid.number(this.form.card_number)
            if (number.card) {
                return number.card.niceType
            }
        },
    },

    watch: {
        amount(val) {
            if (this.isDeposit  && this.package.allow_deposit) {
                this.form.status = false
            } else {
                this.form.status = true
            }
            this.form.amount = val
        },
        rateId(val) {
            this.form.rate_id = val
        },
        exp_menu (val) {
            val && this.$nextTick(() => (this.$refs.exp_picker.activePicker = 'YEAR'))
        },
    },

    mounted() {

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
                        });
            }
        },
        getStatus() {
            this.loading = true
            let endpoint = 'tours/' + this.id + '/status'
            axios.get(endpoint)
                .then(response => {
                    store.setStatus(response.data.data)
                    this.bookable = this.shared.status.is_bookable
                    this.loading = false
                }, error => {
                    // TODO handle error
                });
        },
        clear () {
            this.$v.$reset()
            this.form.reset()
        },
        addRoom(event, travelerCount = 1) {
            if (this.roomsAvailable === 0) {
                this.$notify({ group: 'package', type: 'warn', title: 'Tour Is Full!', text: 'All rooms on this tour are full.'});
                return false
            }
            var newRoom = this.form.rooms.push({travelers:[], rate:[], premium:''}) -1;
            for(let i = 0, length1 = travelerCount; i < length1; i++){
                this.addAdult(newRoom);
            }
        },
        updateRoom(room) {
            this.form.rooms[room.index].rate = room.rate
            this.form.rooms[room.index].premium = room.premium
            this.form.rooms[room.index].rate_id = room.rate.id
            this.form.rooms[room.index].premium_id = room.premium.id
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
            if (this.hasVacancy(roomIndex)) {
                this.form.rooms[roomIndex].travelers.push({
                    first_name:'',
                    middle_name:'',
                    last_name:'',
                    birthdate: '',
                    gender: '',
                    adult: adult,
                    insurance: false,
                    seat_preference: '',
                    country: '',
                    state: '',
                    email: '',
                    ffp: '',
                    passport: '',
                    ktn: '',
                });
            }
        },
        removeTraveler(index) {
            this.form.rooms[index.room].travelers.splice(index.traveler, 1);
        },
        getRoomCount(roomIndex) {
            return this.form.rooms[roomIndex].travelers.length;
        },
        occupancy_rate(count) {
            let rate = this.package.rates.filter(rate => rate.adult == count && rate.child == 0)
            let perPerson = rate[0].price / count

            return perPerson
        },
        occupancy_count(count) {
            switch (count) {
                case 1:
                    return 'Single'
                    break;
                case 2:
                    return 'Double'
                    break;
                case 3:
                    return 'Triple'
                    break;
                case 4:
                    return 'Quad'
                    break;
                default:
                    return count
            }
        },
        nextStep(step) {
            this.$v.$touch()
            if (! this.$v.$invalid) {
                this.step = step
            }
        },
        hasVacancy(roomIndex) {
            return this.getRoomCount(roomIndex) < this.roomMax;
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
