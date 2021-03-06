import Vue from 'vue';
import axios from 'axios';
import _ from 'lodash';
import moment from 'moment';
import Form from './utilities/Form';
import Event from './utilities/EventBus';
import store from './utilities/Store';

window.Vue = Vue;
window._ = _;
window.axios = axios;
window.moment = moment;
window.Form = Form;
window.Event = Event;
window.store = store;

let tld = (process.env.NODE_ENV === 'development' ? 'test' : 'com');

let api = {
    url: 'https://www.pandaonline.' + tld + '/wp-json/',
    namespace: 'ptpkg/v1/',
}

window.axios.defaults.baseURL = api.url + api.namespace;

window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json',
    'Content-Type': 'application/json',
};

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-WP-Nonce'] = token.content;
} else {
    console.error('CSRF token not found');
}

/**
 * Set the authData object. The content of this object is used to tie the
 * payment nonce to your individual Authorize.Net account.
 */

let clientKey = document.head.querySelector('meta[name="clientKey"]');
let apiLoginID = document.head.querySelector('meta[name="apiLoginID"]');
window.authData = {};

if (clientKey) {
    window.authData.clientKey = clientKey.content;
}

if (apiLoginID) {
    window.authData.apiLoginID = apiLoginID.content;
}
