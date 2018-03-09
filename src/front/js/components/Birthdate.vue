<template>
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
        <slot></slot>
        <v-text-field
          v-model="value.birthdate"
          :value="value.birthdate"
          ref="birthdate"
          slot="activator"
          label="Birthdate"
          hint="yyyy-mm-dd"
          prepend-icon="event"
          :name="'birthdate-' + index.room + '-' + index.traveler"
          :id="'birthdate-' + index.room  + '-' + index.traveler"
          :error-messages="birthdateErrors({room:index.room, traveler:index.traveler})"
          @input="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].birthdate.$touch()"
          @blur="$v.form.rooms.$each[index.room].travelers.$each[index.traveler].birthdate.$touch()"
          @birthdate="updateBirthdate()"
          readonly
          required></v-text-field>
        <v-date-picker
          ref="picker"
          v-model="value.birthdate"
          @change="save"
          min="1930-01-01"
          :max="new Date().toISOString().substr(0, 10)"></v-date-picker>
      </v-menu>
</template>

<script>
import errors from '../mixins/errors'

export default {
    name: 'birthdate',

    mixins: [errors],

    props: [
        'value',
        'index',
        '$v',
    ],

    data() {
        return {
            date: null,
            menu: false,
        }
    },

    watch: {
        menu(val) {
            val && this.$nextTick(() => (this.$refs.picker.activePicker = 'YEAR'))
        }
    },

    methods: {
        updateBirthdate() {
            this.$emit('birthdate', {
                birthdate: +this.$refs.birthdate.value,
            })
        },
        save(date) {
            this.$refs.menu.save(date)
        },
    },
}
</script>
