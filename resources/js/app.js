import Vue from 'vue';
import Routes from './routes';
import VueRouter from 'vue-router';
import axios from 'axios';

Vue.use(VueRouter);

let token = document.head.querySelector('meta[name="csrf-token"]');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

Vue.prototype.$http = axios.create();

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: LaravelUnused.route_prefix,
});

Vue.component('prism', Prism);

new Vue({
    el: '#laravel-unused',
    router,
});
