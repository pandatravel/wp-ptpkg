import {withParams, req} from 'vuelidate/lib/validators/common'
import valid from 'card-validator'

export default withParams({type: 'creditCard'}, value => {
        var number = valid.number(value)
        if (! number.isPotentiallyValid) {
            return false;
        }

        return number.isValid
    })


// Test Credit Card Numbers
// American Express: 378282246310005
// Diner's Club: 38520000023237
// Discover: 6011454931724887
// JCB: 3566002020360505
// Maestro: 6763946698976220
// MasterCard: 5206034443216817
// UnionPay: 6234253249408910
// Visa: 4012888888881881
