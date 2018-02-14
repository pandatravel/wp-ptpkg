
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
        totalAdults() {
            var total = 0;
            this.form.rooms.forEach(function (room) {
                room.travelers.forEach(function (traveler) {
                    if (traveler.adult) {
                        total++;
                    }
                })
            });
            return total;
        },
        totalChildren() {
            var total = 0;
            this.form.rooms.forEach(function (room) {
                room.travelers.forEach(function (traveler) {
                    if (! traveler.adult) {
                        total++;
                    }
                })
            });
            return total;
        },
        rateTier() {
            var rate = this.package.rates.filter(this.rateFilterCallback(this.totalAdults, this.totalChildren));
            return rate;
        },
        priceTotal() {
            return this.priceSubTotal + this.insuranceTotal;
        },
        priceSubTotal() {
            if (this.rateTier.length == 1) {
                return this.rateTier[0].price;
            }
        },
        insuranceTotal() {
            var travelers = this.totalAdults + this.totalChildren;
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
            if (this.hasVacancy(roomIndex)) {
                this.form.rooms[roomIndex].travelers.push({
                    first_name:'',
                    middle_name:'',
                    last_name:'',
                    birthdate:'',
                    gender: '',
                    adult: true
                });
            }
        },
        addChild(roomIndex) {
            if (this.hasVacancy(roomIndex)) {
                this.form.rooms[roomIndex].travelers.push({
                    first_name:'',
                    middle_name:'',
                    last_name:'',
                    birthdate:'',
                    gender: '',
                    adult: false
                });
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
        rateFilterCallback(adults, children) {
            return function(rate) {
                return rate.adult == adults && rate.child == children;
            }
        }

    }

});
