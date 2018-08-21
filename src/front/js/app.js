import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css'
import 'babel-polyfill';
import VueCurrencyFilter from 'vue-currency-filter'
import Notifications from 'vue-notification';
import ActionButton from './components/ActionButton.vue';
import CornerRibbon from './components/CornerRibbon.vue';
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import './index';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Vuetify);
Vue.use(Notifications);
Vue.use(VueCurrencyFilter, {
  symbol : '$',
  thousandsSeparator: ',',
  fractionCount: 2,
  fractionSeparator: '.',
  symbolPosition: 'front',
  symbolSpacing: false
});

Vue.component('action-button', ActionButton);
Vue.component('corner-ribbon', CornerRibbon);

const app = new Vue({
    el: '#app'
});

