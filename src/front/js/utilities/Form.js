import Errors from './Errors';

class Form {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }


    /**
     * Fetch all relevant data for the form.
     */
    data() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }


    /**
     * Reset the form fields.
     */
    reset() {
        for (let field in this.originalData) {
            this[field] = (_.isArray(this[field]) ? [] : '');
        }

        this.errors.clear();
    }


    /**
     * Send a POST request to the given URL.
     * .
     * @param {string} url
     */
    post(url) {
        return this.submit('post', url);
    }


    /**
     * Send a PUT request to the given URL.
     * .
     * @param {string} url
     */
    put(url) {
        return this.submit('put', url);
    }


    /**
     * Send a PATCH request to the given URL.
     * .
     * @param {string} url
     */
    patch(url) {
        return this.submit('patch', url);
    }


    /**
     * Send a DELETE request to the given URL.
     * .
     * @param {string} url
     */
    delete(url) {
        return this.submit('delete', url);
    }

    /**
     * Send a payment data to authorize.net to get a secure token
     *
     * @param {object} secureData
     */
    authnet(secureData) {
        return new Promise((resolve, reject) => {
            let expDate = moment(secureData.cardData.cardExpiration, 'YYYY-MM')
            secureData.cardData.month = expDate.format('MM')
            secureData.cardData.year = expDate.format('YYYY')
            secureData.cardData.zip = this.zip
            secureData.cardData.fullName = this.name
            delete secureData.cardData.cardExpiration
            Accept.dispatchData(secureData, response => {
                 if (response.messages.resultCode === "Ok") {
                    this.opaqueData = response.opaqueData;

                    resolve(response);
                } else {
                    response.messages.message.forEach(msg => console.log(msg.code + ": " + msg.text))
                    this.onFail(response.messages.message)

                    reject(response);
                }
            });
        })
    }


    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     */
    submit(requestType, url) {
        return new Promise((resolve, reject) => {
            axios[requestType](url, this.data())
                .then(response => {
                    this.onSuccess(response.data);

                    resolve(response.data);
                })
                .catch(error => {
                    this.onFail(error.response.data);

                    reject(error.response.data);
                });
        });
    }


    /**
     * Handle a successful form submission.
     *
     * @param {object} data
     */
    onSuccess(data) {

    }


    /**
     * Handle a failed form submission.
     *
     * @param {object} errors
     */
    onFail(errors) {
        this.errors.record(errors);
    }
}

export default Form;
