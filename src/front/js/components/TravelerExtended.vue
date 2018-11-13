<template>
    <div>
        <v-layout v-if="step == 2" wrap>
            <v-flex sm2 xs12>
                {{ fullName }} {{ (value.adult ? '(Adult)': '(Child ' + age + 'yrs)') }}
            </v-flex>
            <v-flex sm2 xs12>
                <v-text-field
                    v-model="value.ffp"
                    :value="value.ffp"
                    ref="ffp"
                    label="Frequent Flyer Number"
                    :name="'ffp' + indexId"
                    :id="'ffp' + indexId"
                    @traveler="updateTraveler()"></v-text-field>
            </v-flex>
            <v-flex sm2 xs12>
                <v-select
                    v-model="value.seat_preference"
                    ref="seat_preference"
                    label="Seat Preference"
                    :name="'seat_preference' + indexId"
                    :id="'seat_preference' + indexId"
                    :items="['Any Seat', 'Aisle', 'Window']"
                    @traveler="updateTraveler()"></v-select>
            </v-flex>
            <v-flex sm3 xs12>
                <v-select
                    v-model="value.country"
                    ref="country"
                    label="Country of Residence *"
                    :name="'country' + indexId"
                    :id="'country' + indexId"
                    :items="countries"
                    item-text="country"
                    item-value="code"
                    :error-messages="countryErrors({room:index.room, traveler:index.traveler})"
                    @input="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].country.$touch()"
                    @blur="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].country.$touch()"
                    @traveler="updateTraveler()"
                    dense
                    autocomplete
                    required></v-select>
            </v-flex>
            <v-flex sm3 xs12>
                <v-select
                    v-model="value.state"
                    ref="state"
                    label="State of Residence *"
                    :name="'state' + indexId"
                    :id="'state' + indexId"
                    :items="states"
                    item-text="state"
                    item-value="code"
                    :error-messages="stateErrors({room:index.room, traveler:index.traveler})"
                    @input="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].state.$touch()"
                    @blur="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].state.$touch()"
                    @traveler="updateTraveler()"
                    dense
                    autocomplete
                    required></v-select>
            </v-flex>
            <v-flex sm2 xs12>
            </v-flex>
            <v-flex sm3 xs12>
                <v-text-field
                    v-model="value.passport"
                    :value="value.passport"
                    ref="passport"
                    label="Passport Number"
                    :name="'passport' + indexId"
                    :id="'passport' + indexId"
                    @traveler="updateTraveler()"></v-text-field>
            </v-flex>
            <v-flex sm3 xs12>
                <v-text-field
                    v-model="value.ktn"
                    :value="value.ktn"
                    ref="ktn"
                    label="Known Traveler Number"
                    :name="'ktn' + indexId"
                    :id="'ktn' + indexId"
                    @traveler="updateTraveler()"></v-text-field>
            </v-flex>
        </v-layout>
        <v-divider></v-divider>
    </div>
</template>

<script>
import errors from '../mixins/errors'
import countries from '../mixins/countries'
import states from '../mixins/states'
import Moment from 'moment'

export default {
    name: 'traveler-extended',

    mixins: [
        errors,
        countries,
        states,
    ],

    props: [
        'value',
        'index',
        '$v',
        'step'
    ],

    data() {
        return {
            traveler: this.value,
        }
    },

    computed: {
        indexId() {
            return '-' + this.index.room + '-' + this.index.traveler;
        },
        fullName() {
            return this.traveler.first_name + ' ' + this.traveler.middle_name + ' ' + this.traveler.last_name
        },
        age() {
            return this.traveler.birthdate ? Moment().diff(this.traveler.birthdate, 'years') : ''
        }
    },
    methods: {
        updateTraveler() {
            this.$emit('traveler', {
                ffp: +this.$refs.ffp.value,
                seat_preference: +this.$refs.seat_preference.value,
                country: +this.$refs.country.value,
                state: +this.$refs.state.value,
                insurance: +this.$refs.insurance.value,
                passport: +this.$refs.passport.value,
                ktn: +this.$refs.ktn.value,
            })
        },
    },
}
</script>
