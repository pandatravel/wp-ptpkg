<template>
    <div v-if="! bookable" class="ribbon " :class=" color + ' ' + position"><span>{{ label }}</span></div>
</template>

<script>
    export default {
        name: 'corner-ribbon',
        props: {
            'color': {
                type: String,
                default: function() {
                    return 'primary';
                }
            },
            'corner': {
                type: String,
                default: function() {
                    return 'top-left';
                }
            },
        },

        data() {
            return {
                position: 'ribbon-' + this.corner,
                shared: store.state,
            }
        },

        computed: {
            bookable() {
                if (this.shared.status) {
                    return this.shared.status.is_bookable
                }
                return true
            },
            label() {
                let label = ''
                if (! this.bookable) {
                    if (this.shared.status.message) {
                        if (this.shared.status.message == 'Tour is cancelled') {
                            label = 'cancelled'
                        } else if (this.shared.status.message == 'Tour is sold out') {
                            label = 'Sold Out'
                        } else if (this.shared.status.message == 'Tour booking period has expired') {
                            label = 'expired'
                        } else {
                            label = 'not available'
                        }
                    } else if (this.shared.status.is_sold_out) {
                        label = 'Sold Out'
                    } else if (this.shared.status.is_cancelled) {
                        label = 'Cancelled'
                    } else if (moment().isAfter(moment(this.shared.status.booking_end_at, 'MM/DD/YYYY'))) {
                        label = 'Expired'
                    } else if (! this.shared.status.active) {
                        label = 'Not Available'
                    }
                }
                return label
            }
        },

        methods: {

        },
    }
</script>
