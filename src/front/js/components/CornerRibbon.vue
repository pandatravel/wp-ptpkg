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
            'position': {
                type: String,
                default: function() {
                    return 'ribbon-top-left';
                }
            },
        },

        data() {
            return {
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
                    if (this.shared.status.is_sold_out) {
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
