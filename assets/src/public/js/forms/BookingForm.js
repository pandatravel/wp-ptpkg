
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
                name: '',
                email: '',
            }),
            step:1,
            submiting: false,
        }
    },

    mounted() {
        // console.log(this.form)
    },

    methods: {
        onSubmit() {
            this.submiting = true;

            this.form.post(this.endpoint)
                     .then(data => console.log(data))
                     .catch(errors => console.log(errors));
        },
        validate(event) {
            this.$validator.errors.remove(event.target.name);
        },

    }

});
