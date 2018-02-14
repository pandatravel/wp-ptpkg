
Vue.component('booking-form', {
    props: {
        'data': {
            type: Object,
            default: function() {
                return {};
            }
        },
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
                id: '',
                rooms: [],
            }),
            step:1,
            room_max: 3,
            submiting: false,
            package: window._ptpkgAPIDataPreload,
        }
    },

    created() {
        this.form.id = this.package.id;
        this.room_max = this.package.room_max;

        this.addRoom();
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
            var rate = this.package.rates.filter(this.rateFilter(this.adults, this.children));
            return rate;
        },
        subTotal() {
            return this.package.rates.reduce(this.rateReducer, 0);
        },
        total() {
            return this.subTotal + this.insurance;
        },
        insurance() {
            var travelers = this.adults + this.children;
            return 0;
        },
    },

    methods: {
        onSubmit() {
            this.submiting = true;

            this.form.post(this.endpoint)
                     .then(data => console.log(data))
                     .catch(errors => console.log(errors));
        },
        addRoom() {
            var newRoom = this.form.rooms.push({travelers:[]}) -1;
            this.addAdult(newRoom);
        },
        removeRoom(index) {
            this.form.rooms.splice(index, 1)
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
                        birthdate:'',
                        gender: '',
                        adult: adult
                    });
                }
            }
        },
        removeTraveler(roomIndex, travelerIndex) {
            this.form.rooms[roomIndex].travelers.splice(travelerIndex, 1);
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
        rateReducer(price, rate) {
            if (rate.adult == this.adults && rate.child == this.children) {
                price = rate.price;
            }
            return price;
        },
    }

});
