<template>
    <v-btn
        :href="href"
        :large="large"
        :block="block"
        :round="btnRound"
        :color="btnColor"
        :outline="outline"
        :title="title"
        :loading="shared.loading">{{ btnLabel }}</v-btn>
</template>

<script>
    export default {
        name: 'action-button',

        props: {
            'url': {
                type: String,
                required: true,
                default: function() {
                    return null;
                }
            },
            'id': {
                type: Number,
                required: true,
                default: function() {
                    return null;
                }
            },
            'color': {
                type: String,
                default: function() {
                    return 'primary';
                }
            },
            'label': {
                type: String,
                default: function() {
                    return 'Book Now';
                }
            },
            'title': {
                type: String,
                default: function() {
                    return 'Start Booking This Package Now';
                }
            },
            'value': {
                default: function() {
                    return null;
                }
            },
            'large': {
                type: Boolean,
                default: function() {
                    return false;
                }
            },
            'small': {
                type: Boolean,
                default: function() {
                    return false;
                }
            },
            'block': {
                type: Boolean,
                default: function() {
                    return false;
                }
            },
            'round': {
                type: Boolean,
                default: function() {
                    return false;
                }
            },
            'outline': {
                type: Boolean,
                default: function() {
                    return false;
                }
            },
            'loadStatus': {
                type: Boolean,
                default: function() {
                    return false;
                }
            },
        },

        data() {
            return {
                endpoint: 'tours/' + this.id + '/status',

                shared: store.state,
            }
        },

        mounted() {
            if (this.loadStatus) {
                this.getStatus()
            }
        },

        computed: {
            bookable() {
                if (this.shared.status) {
                    return this.shared.status.is_bookable
                }
                return true
            },
            btnColor() {
                if (! this.bookable) {
                    return 'normal'
                }
                return this.color
            },
            btnRound() {
                if (! this.bookable) {
                    return false
                }
                return this.round
            },
            href() {
                if (! this.bookable) {
                    return null
                }
                return this.url
            },
            btnLabel() {
                let label = this.label
                if (! this.bookable) {
                    if (this.shared.status.is_cancelled) {
                        label = 'Tour Cancelled'
                    } else {
                        label = this.shared.status.status
                    }
                }
                return label.replace(/_/g, ' ')
            }
        },

        methods: {
            getStatus() {
                store.setLoading(true)

                axios.get(this.endpoint)
                    .then(response => {
                        store.setStatus(response.data.data)
                        store.setLoading(false)
                    }, error => {
                        // TODO handle error
                    });
            },
        },
    }
</script>
