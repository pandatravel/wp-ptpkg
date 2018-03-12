<template>
    <v-layout row pb-1>
        <v-flex xs12>
            <v-card class="card--flex-toolbar mx-1 my-1">
                <v-toolbar card light dense>
                    <v-toolbar-title class="body-2 grey--text">Room {{ index + 1 }}</v-toolbar-title>
                    <v-toolbar-items>
                        <v-btn @click="removeRoom" flat dark color="red"><v-icon dark>close</v-icon> remove</v-btn>
                    </v-toolbar-items>
                    <v-spacer></v-spacer>
                    <span class="caption grey--text text--darken-1">Price: {{ perPersonRate | currency }} per person</span>
                    <v-spacer></v-spacer>
                    <span class="caption grey--text text--darken-1">Insurance: {{ premium | currency }} per person</span>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn @click="addAdult" :disabled="hasVacancy == false" color="info" flat><v-icon dark>add</v-icon> Adult &nbsp;<small>(19+)</small></v-btn>
                        <v-btn @click="addChild" :disabled="hasVacancy == false" color="info" flat><v-icon dark>add</v-icon> Child &nbsp;<small>(2-18)</small></v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-divider class="mt-0"></v-divider>
                <v-container grid-list-xl fluid class="px-3 py-1">
                    <traveler v-for="(traveler, travelerIndex) in room.travelers" :key="'traveler-' + travelerIndex" v-model="room.travelers[travelerIndex]" :index="{traveler:travelerIndex, room:index}" :$v="$v" @remove-traveler="removeTraveler"></traveler>
                </v-container>
            </v-card>
        </v-flex>
    </v-layout>
</template>

<script>
import traveler from './Traveler.vue';

  export default {
    name: 'room',
    components: {traveler},

    props: [
        'room',
        'index',
        'rates',
        'premiums',
        'room_max',
        '$v',
    ],

    data() {
        return {

        }
    },

    mounted() {
        this.updateRoom()
    },

    computed: {
        count() {
            return this.room.travelers.length
        },
        hasVacancy() {
            return this.count < this.roomMax
        },
        adults() {
            return this.room.travelers.reduce(function(adults, traveler) {
                return (traveler.adult ? adults + 1 : adults);
            }, 0);
        },
        children() {
            return this.room.travelers.reduce(function(children, traveler) {
                return (! traveler.adult ? children + 1 : children);
            }, 0);
        },
        roomMax() {
            return this.rates.reduce(function(max, rate) {
                var total = rate.adult + rate.child;
                return (total > max ? total : max);
            }, 0);
        },
        rateTier() {
            if (this.count == 0) {
                return []
            }
            var rate = this.rates.filter(this.rateFilter(this.adults, this.children))
            return rate[0]
        },
        rateId() {
            return this.rateTier.id
        },
        roomTotal() {
            return Number(this.rates.reduce(this.rateReducer, 0))
        },
        perPersonRate() {
            if (this.count == 0) {
                return 0
            }
            return this.roomTotal / this.count;
        },
        premiumTier() {
            if (this.count == 0) {
                return []
            }
            var premium = this.premiums.filter(this.premiumFilter(this.perPersonRate))
            return premium[0]
        },
        premium() {
            return Number(this.premiums.reduce(this.premiumReducer, 0));
        },
        premiumTotal() {
            return this.premium * this.count
        }
    },

    watch: {
        rateTier() {
            this.updateRoom()
        }
    },

    methods: {
        updateRoom() {
            // console.log('updateRoom fired: ' + this.rateTier.price)
            this.$emit('update-room', {
                index: this.index,
                rate: this.rateTier,
                premium: this.premiumTotal,
            })
        },
        addAdult() {
            this.addTraveler(true);
        },
        addChild() {
            this.addTraveler(false);
        },
        addTraveler(adult = true) {
            if (this.hasVacancy) {
                this.room.travelers.push({
                    first_name:'',
                    middle_name:'',
                    last_name:'',
                    birthdate: null,
                    gender: '',
                    adult: adult,
                    ffp: '327709212',
                    seat_preference: 'Window Seat',
                    country: 'US',
                    state: 'HI',
                });
            }
        },
        removeRoom() {
            this.$emit('remove-room', this.index)
        },
        removeTraveler(index) {
            this.$emit('remove-traveler', index)
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
        premiumFilter(perPersonRate) {
            return function(premium) {
                return perPersonRate >= premium.range_start && perPersonRate <=premium.range_end;
            }
        },
        premiumReducer(price, premium) {
            if (this.perPersonRate >= premium.range_start && this.perPersonRate <= premium.range_end) {
                price = premium.price;
            }
            return price;
        },
    },
  }
</script>
