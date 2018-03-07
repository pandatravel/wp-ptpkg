import {withParams, req} from 'vuelidate/lib/validators/common'
import valid from 'card-validator'

export default withParams({type: 'creditCard'}, value => {
        var number = valid.number(value)
        if (! number.isPotentiallyValid) {
            return false;
        }

        return number.isValid
    })
