import Vue from 'vue';

const Event = new class {
    constructor() {
        this.bus = new Vue();
    }

    get bus() {
        return this.bus;
    }

    fire(event, data = null) {
        this.bus.$emit(event, data);
    }

    listen(event, callback) {
        this.bus.$on(event, callback);
    }

    off(event = null, callback = null) {
        this.bus.$off(event, callback);
    }
}

export default Event;
