import {withParams, ref} from 'vuelidate/lib/validators/common'

export default (min, max) =>
    withParams({type: 'ageRange', min, max}, (value, parentVm) => {
        let age = moment().diff(value, 'years')

        if (parentVm.adult) {
            return age > max
        }

        return age >= min && age <= max
    })
