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
            // !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].birthdate.ageRage && errors.push('Childs age must be between 2 - 18 years old')
            !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].birthdate.required && errors.push('Birthdate is required.')
            return errors
        },
        genderErrors (index) {
            const errors = []
            if (!this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].gender.$dirty) return errors
            !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].gender.required && errors.push('Gender is required.')
            return errors
        },
        countryErrors (index) {
            const errors = []
            if (!this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].country.$dirty) return errors
            !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].country.required && errors.push('Country is required.')
            return errors
        },
        stateErrors (index) {
            const errors = []
            if (!this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].state.$dirty) return errors
            !this.$v.form.rooms.$each[index.room].travelers.$each[index.traveler].state.required && errors.push('State is required.')
            return errors
        },
        agreeTermsErrors () {
            const errors = []
            if (!this.$v.form.agree_terms.$dirty) return errors
            !this.$v.form.agree_terms.required && errors.push('You must agree to the Terms & Conditions.')
            return errors
        },
        billingAddressErrors () {
            const errors = []
            if (!this.$v.form.address.$dirty) return errors
            !this.$v.form.address.required && errors.push('City is required.')
            return errors
        },
        billingCityErrors () {
            const errors = []
            if (!this.$v.form.city.$dirty) return errors
            !this.$v.form.city.required && errors.push('City is required.')
            return errors
        },
        billingCountryErrors () {
            const errors = []
            if (!this.$v.form.country.$dirty) return errors
            !this.$v.form.country.required && errors.push('Country is required.')
            return errors
        },
        billingStateErrors () {
            const errors = []
            if (!this.$v.form.state.$dirty) return errors
            !this.$v.form.state.required && errors.push('State is required.')
            return errors
        },
        billingZipErrors () {
            const errors = []
            if (!this.$v.form.zip.$dirty) return errors
            !this.$v.form.zip.required && errors.push('Zip/Postal Code is required.')
            return errors
        },
        phoneErrors () {
            const errors = []
            if (!this.$v.form.phone.$dirty) return errors
            !this.$v.form.phone.required && errors.push('Phone is required.')
            return errors
        },
        emailErrors () {
            const errors = []
            if (!this.$v.form.email.$dirty) return errors
            !this.$v.form.email.required && errors.push('Email is required.')
            !this.$v.form.email.email && errors.push('Must be a valid email address.')
            return errors
        },
        emailConfirmErrors () {
            const errors = []
            if (!this.$v.form.email_confirm.$dirty) return errors
            !this.$v.form.email_confirm.required && errors.push('Email Confirmation is required.')
            !this.$v.form.email_confirm.email && errors.push('Must be a valid email address.')
            !this.$v.form.email_confirm.sameAs && errors.push('Email Confirmation must match Email field.')
            return errors
        },
        cardNameErrors () {
            const errors = []
            if (!this.$v.form.card_name.$dirty) return errors
            !this.$v.form.card_name.required && errors.push('Cardholder Name is required.')
            return errors
        },
        cardTypeErrors () {
            const errors = []
            if (!this.$v.form.card_type.$dirty) return errors
            !this.$v.form.card_type.required && errors.push('Card Type is required.')
            return errors
        },
        cardNumberErrors () {
            const errors = []
            if (!this.$v.form.card_number.$dirty) return errors
            !this.$v.form.card_number.required && errors.push('Card Number is required.')
            !this.$v.form.card_number.between && errors.push('Credit Card Number is invalid.')
            return errors
        },
        cardExpirationErrors () {
            const errors = []
            if (!this.$v.form.card_expiration.$dirty) return errors
            !this.$v.form.card_expiration.required && errors.push('Card Expiration is required.')
            return errors
        },
        cardCvvErrors () {
            const errors = []
            if (!this.$v.form.card_cvv.$dirty) return errors
            !this.$v.form.card_cvv.required && errors.push('Card CVV is required.')
            return errors
        },
    }
}
