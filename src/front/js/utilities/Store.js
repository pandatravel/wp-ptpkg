const store = {
  debug: true,
  state: {
    status: ''
  },
  setStatus (newValue) {
    if (this.debug) console.log('setStatus triggered with', newValue)
    this.state.status = newValue
  },
  clearStatus () {
    if (this.debug) console.log('clearStatus triggered')
    this.state.status = ''
  }
}

export default store;
