import {withParams, ref} from 'vuelidate/lib/validators/common'
import Moment from 'moment'

export default (min, max) =>
    withParams({type: 'ageRange', min, max}, (value, parentVm) => {
        let age = Moment().diff(value, 'years')

        if (parentVm.adult) {
            return age > max
        }

        return age >= min && age <= max
    })
