export default {
    name: 'errors',
    methods: {
        firstNameErrors (index) {
            const errors = []
            if (!this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].first_name.$dirty) return errors
            // !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].first_name.maxLength && errors.push('First Name must be at most 10 characters long')
            !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].first_name.required && errors.push('First Name is required.')
            return errors
        },
        lastNameErrors (index) {
            const errors = []
            if (!this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].last_name.$dirty) return errors
            // !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].last_name.maxLength && errors.push('Last Name must be at most 10 characters long')
            !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].last_name.required && errors.push('Last Name is required.')
            return errors
        },
        birthdateErrors (index) {
            const errors = []
            if (!this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].birthdate.$dirty) return errors
            !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].birthdate.required && errors.push('Birthdate is required.')
            return errors
        },
        genderErrors (index) {
            const errors = []
            if (!this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].gender.$dirty) return errors
            !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].gender.required && errors.push('Gender is required.')
            return errors
        },
        agreeTermsErrors () {
            const errors = []
            if (!this.$v.form.agree_terms.$dirty) return errors
            !this.$v.form.agree_terms.required && errors.push('You must agree to the Terms & Conditions.')
            return errors
        },
    }
}
