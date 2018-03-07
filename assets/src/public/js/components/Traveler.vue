<template>
    <v-layout wrap>
        <v-flex xs3>
            <v-text-field
                v-model="value.first_name"
                :value="value.first_name"
                ref="first_name"
                label="First Name"
                :name="'first_name' + indexId"
                :id="'first_name' + indexId"
                persistent-hint
                :hint="value.adult ? 'Adult (19+)' : 'Child (2-18)'"
                :error-messages="firstNameErrors({room:index.room, traveler:index.traveler})"
                @input="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].first_name.$touch()"
                @blur="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].first_name.$touch()"
                @traveler="updateTraveler()"></v-text-field>
        </v-flex>
        <v-flex xs2>
            <v-text-field
                v-model="value.middle_name"
                :value="value.middle_name"
                ref="middle_name"
                label="Middle Name"
                :name="'middle_name' + indexId"
                :id="'middle_name' + indexId"
                @traveler="updateTraveler()"></v-text-field>
        </v-flex>
        <v-flex xs2>
            <v-text-field
                v-model="value.last_name"
                :value="value.last_name"
                ref="last_name"
                label="Last Name"
                :name="'last_name' + indexId"
                :id="'last_name' + indexId"
                :error-messages="lastNameErrors({room:index.room, traveler:index.traveler})"
                @input="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].last_name.$touch()"
                @blur="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].last_name.$touch()"
                @traveler="updateTraveler()"></v-text-field>
        </v-flex>
        <v-flex xs2>
            <v-menu
                ref="menu"
                lazy
                :close-on-content-click="false"
                v-model="menu"
                transition="scale-transition"
                offset-y
                full-width
                :nudge-right="40"
                min-width="290px">
                <v-text-field
                  v-model="value.birthdate"
                  :value="value.birthdate"
                  slot="activator"
                  :name="'birthdate' + indexId"
                  label="Birthdate"
                  hint="yyyy-mm-dd"
                  prepend-icon="event"
                  :error-messages="birthdateErrors({room:index.room, traveler:index.traveler}, age, value.adult)"
                  @input="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].birthdate.$touch()"
                  @blur="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].birthdate.$touch()"
                  @traveler="updateTraveler()"
                  readonly></v-text-field>
                <v-date-picker
                  ref="picker"
                  v-model="value.birthdate"
                  @change="save"
                  min="1930-01-01"
                  :max="new Date().toISOString().substr(0, 10)"></v-date-picker>
              </v-menu>
        </v-flex>
        <v-flex xs2>
            <v-select
                v-model="value.gender"
                :value="value.gender"
                ref="gender"
                label="Gender"
                :name="'gender' + indexId"
                :id="'gender' + indexId"
                :items="['Male', 'Female']"
                :error-messages="genderErrors({room:index.room, traveler:index.traveler})"
                @input="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].gender.$touch()"
                @blur="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].gender.$touch()"
                @traveler="updateTraveler()"></v-select>
        </v-flex>
        <v-flex xs1>
            <v-btn @click="removeTraveler" color="red" small flat dark fab><v-icon>delete_forever</v-icon></v-btn>
        </v-flex>
    </v-layout>
</template>

<script>
import errors from '../mixins/errors'
import countries from '../mixins/countries'
import states from '../mixins/states'
import Moment from 'moment'

export default {
    name: 'traveler',

    mixins: [errors],

    props: [
        'value',
        'index',
        'age',
        '$v',
    ],

    data() {
        return {
            traveler: this.value,
            date: null,
            menu: false,
        }
    },

    computed: {
        indexId() {
            return '-' + this.index.room + '-' + this.index.traveler;
        },
    },

    watch: {
        menu(val) {
            val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
        }
    },

    methods: {
        updateTraveler() {
            this.$emit('traveler', {
                first_name: +this.$refs.first_name.value,
                middle_name: +this.$refs.middle_name.value,
                last_name: +this.$refs.last_name.value,
                birthdate: +this.$refs.birthdate.value,
                gender: +this.$refs.gender.value,
            })
        },
        removeTraveler() {
            this.$emit('remove-traveler', this.index)
        },
        save(date) {
            this.$refs.menu.save(date)
        }
    },
}
</script>
