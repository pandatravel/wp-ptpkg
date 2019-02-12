const payment = {

    data() {
        return {
            secureData: {
                authData: window.authData,
                cardData: {
                    cardNumber: '',
                    month: '',
                    year: '',
                    cardCode: '',
                    zip: '',
                    fullName: '',
                    cardExpiration: '',
                }
            }
        }
    },

};

export default payment;
