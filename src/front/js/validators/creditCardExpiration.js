import {withParams} from 'vuelidate/lib/validators/common'
import valid from 'card-validator'

export default withParams({type: 'creditCardExpiration'}, value => {
        var date = value.split('-')
        var expDate = {month: date[1], year: date[0]}
        var expirationDate = valid.expirationDate(expDate)
        if (! expirationDate.isPotentiallyValid) {
            return false;
        }

        return expirationDate.isValid
    })
