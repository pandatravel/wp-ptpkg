const store = {
    debug: true,
    state: {
        status: '',
        loading: '',
    },
    setStatus (newValue) {
        if (this.debug) console.log('setStatus triggered with', newValue)
        this.state.status = newValue
    },
    clearStatus () {
        if (this.debug) console.log('clearStatus triggered')
        this.state.status = ''
    },
    setLoading (newValue) {
        if (this.debug) console.log('setLoading triggered with', newValue)
        this.state.loading = newValue
    },
    clearLoading () {
        if (this.debug) console.log('clearLoading triggered')
        this.state.loading = ''
    },
}

export default store;
