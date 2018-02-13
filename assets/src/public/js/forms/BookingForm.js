
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
                rooms: [],
                email: '',
            }),
            step:1,
            room_max: 3,
            submiting: false,
        }
    },

    mounted() {
        // console.log(this.form)
    },

    created() {
        this.addRoom();
        this.addAdult(0);
    },

    computed: {
        // -
    },

    methods: {
        onSubmit() {
            this.submiting = true;

            this.form.post(this.endpoint)
                     .then(data => console.log(data))
                     .catch(errors => console.log(errors));
        },
        addRoom() {
            this.form.rooms.push({travelers:[]});
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
        }

    }

});
