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
                    <v-toolbar-items>
                        <v-btn @click="addAdult" :disabled="hasVacancy == false || total_travelers >= max_travelers" color="info" flat><v-icon dark>add</v-icon> Adult &nbsp;<small>(19+)</small></v-btn>
                        <v-btn @click="addChild" :disabled="hasVacancy == false || total_travelers >= max_travelers" color="info" flat><v-icon dark>add</v-icon> Child &nbsp;<small>(2-18)</small></v-btn>
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
        'room_max',
        'total_travelers',
        'max_travelers',
        '$v',
    ],

    computed: {
        hasVacancy() {
            return this.room.travelers.length < this.room_max;
        }
    },

    methods: {
        addAdult() {
            this.addTraveler(true);
        },
        addChild() {
            this.addTraveler(false);
        },
        addTraveler(adult = true) {
            if (this.total_travelers < this.max_travelers) {
                if (this.hasVacancy) {
                    this.room.travelers.push({
                        first_name:'',
                        middle_name:'',
                        last_name:'',
                        birthdate: null,
                        gender: '',
                        adult: adult,
                        ffp: '',
                        seat_preference: '',
                        country: '',
                        state: '',
                    });
                }
            }
        },
        removeRoom() {
            this.$emit('remove-room', this.index)
        },
        removeTraveler(index) {
            this.$emit('remove-traveler', index)
        },
    },
  }
</script>
