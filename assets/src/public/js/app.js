import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'
import VueFormWizard from 'vue-form-wizard';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';
import 'babel-polyfill';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import { BookingForm, ToggleSwitch } from './components/index';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Vuetify);
Vue.use(VueFormWizard);
Vue.component('toggle-switch', ToggleSwitch);
Vue.component('booking-form', BookingForm);

const app = new Vue({
    el: '#app'
});
