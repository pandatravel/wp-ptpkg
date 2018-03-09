const creditCardMask = {

    filters: {
        creditCardMask(value) {
            if (!value) {
                return ''
            }

            return value.replace(/\d(?=\d{4})/g, "*")
        },
    }
};

export default creditCardMask;
