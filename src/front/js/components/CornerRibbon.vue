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
                    if (this.shared.status.data) {
                        if (this.shared.status.data.status === 404 || this.shared.status.data.status === 401) {
                            return false
                        }
                    }
                    return this.shared.status.is_bookable
                }
                return true
            },
            label() {
                let label = ''
                if (! this.bookable) {
                    if (this.shared.status.data) {
                        if (this.shared.status.data.status === 404 || this.shared.status.data.status === 401) {
                            label = 'Not Available'
                        }
                    } else {
                        label = this.shared.status.status
                    }
                }
                return label.replace(/_/g, ' ')
            }
        },

        methods: {

        },
    }
</script>
