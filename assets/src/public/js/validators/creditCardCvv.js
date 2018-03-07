import {withParams, ref} from 'vuelidate/lib/validators/common'
import valid from 'card-validator'

export default withParams({type: 'creditCardCvv'}, (value, parentVm) => {
        var number = valid.number(parentVm.card_number)
        var cvv = valid.cvv(value, number.card.code.size)

        if (! cvv.isPotentiallyValid) {
            return false;
        }

        return cvv.isValid
    })
