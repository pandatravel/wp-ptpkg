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
import FontAwesomeIcon from '@fortawesome/vue-fontawesome'
import fontawesome from '@fortawesome/fontawesome'
import brands from '@fortawesome/fontawesome-free-brands'
import faAddressCard from '@fortawesome/fontawesome-free-solid/faAddressCard'
import faCreditCard from '@fortawesome/fontawesome-free-solid/faCreditCard'
import faPhone from '@fortawesome/fontawesome-free-solid/faPhone'
import faFax from '@fortawesome/fontawesome-free-solid/faFax'
import faEnvelope from '@fortawesome/fontawesome-free-solid/faEnvelope'
import faMapMarkerAlt from '@fortawesome/fontawesome-free-solid/faMapMarkerAlt'
import faAsterisk from '@fortawesome/fontawesome-free-solid/faAsterisk'
fontawesome.library.add(brands, faAddressCard, faCreditCard, faPhone, faFax, faEnvelope, faMapMarkerAlt, faAsterisk)

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
        FontAwesomeIcon,
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
                discount_id: '',
                premium: '',
                deposit: '',
                balance: '',
                amount: '',
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
                status: false,
                insurance: false,
                discount: false,
                subscribe: true,
                agree_terms: true,

                rooms: [],

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
        this.form.tour_id = this.package.id
        this.form.code = this.package.code
        this.form.description = this.package.name
        this.form.discount = !this.package.discount ? false : true
        if (this.form.discount) {
            this.form.discount_id = this.package.discount.id
        }
        this.room_max = this.package.room_max

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
        roomMax() {
            return this.package.rates.reduce(function(max, rate) {
                var total = rate.adult + rate.child;
                return (total > max ? total : max);
            }, 0);
        },
        subTotal() {
            return this.form.rooms.reduce((price, room) => Number(price) + Number(room.rate.price), 0)
        },
        amount() {
            let amount = this.subTotal
            if (this.form.discount) {
                amount -= this.discount
            }
            if (! this.isDeposit) {
                this.form.deposit = ''
                this.form.balance = ''
                this.form.amount = amount
                this.form.status = true
            }
            return amount
        },
        total() {
            let total = this.amount
            if (this.form.insurance) {
                total += this.insurance
            }
            return total
        },
        discount() {
            let discount = Number(this.package.discount.amount * this.totalTravelers)
            if (!this.form.discount) {
                return 0
            }
            return discount
        },
        deposit() {
            let deposit = Number(this.package.deposit * this.totalTravelers)
            if (this.isDeposit) {
                this.form.deposit = deposit
                this.form.amount = ''
                this.form.status = false
            }
            return deposit
        },
        depositTotal() {
            let total = this.deposit
            if (this.form.insurance) {
                total += this.insurance
            }
            return total
        },
        perPersonRate() {
            if (this.totalTravelers == 0) {
                return 0;
            }
            return this.subTotal / this.totalTravelers;
        },
        balanceTotal() {
            let balance = this.isDeposit ? Number(this.total - this.depositTotal) : ''
            if (this.isDeposit) {
                this.form.balance = balance
            }
            return balance
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
                insurance = this.form.rooms.reduce((price, room) => Number(price) + Number(room.premium.price * room.travelers.length), 0)
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
            var newRoom = this.form.rooms.push({travelers:[], rate:[], premium:''}) -1;
            this.addAdult(newRoom);
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
