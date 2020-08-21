require('./bootstrap');

window.Vue = require('vue');


Vue.component('app-component', require('./Components/App.vue').default)
Vue.component('app-spinner', require('./Components/widgets/Spinner.vue').default)

import vuetify from './plugins/vuetify'

//suport Rutas
import router from './plugins/routes'

import store from "./store/index"

//Scroolbar

import Vue from 'vue';

//Scroolbar two
import PerfectScrollbar from 'vue2-perfect-scrollbar'
import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css'
Vue.use(PerfectScrollbar)

const app = new Vue({
    el: '#app',
    vuetify,
    router,
    store
});