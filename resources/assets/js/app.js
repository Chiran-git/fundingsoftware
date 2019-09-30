/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.use(VueRouter);
Vue.use(VueSweetalert2);

// Smooth scroll library
var vueSmoothScroll = require('vue-smoothscroll');
Vue.use(vueSmoothScroll);

//V-select
import vSelect from 'vue-select';
Vue.component('v-select', vSelect);

//Vue Trend Chart
import TrendChart from "vue-trend-chart";
Vue.use(TrendChart);

// VueToastr
import VueToastr from "vue-toastr";

Vue.use(VueToastr, {
    defaultTimeout: 7000
});

import TableComponent from 'vue-table-component';

TableComponent.settings({
    filterNoResults: 'There are no matching rows.',
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 */

require('./components/bootstrap');

const routes = [];

const router = new VueRouter({
    routes: routes,
    history: true,
    mode: 'history',
    hashbang: false
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
window.app = new Vue({
    router,
    mixins: [require('./mixins/rj')],
});
