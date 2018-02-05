import axios from 'axios';
import _ from 'lodash';
import Vue from 'vue';
import moment from 'moment';

window.Vue = Vue;
window._ = _;
window.axios = axios;
window.moment = moment;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
