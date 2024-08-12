import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './App.vue';

import './assets/index.scss';

import store from './store/index.js';
import router from './routes/index.js';

import ElementUi from 'element-ui';
import lng from 'element-ui/lib/locale/lang/en.js';

Vue.use(VueRouter);
Vue.use(ElementUi, { locale: lng });

new Vue({
    store,
    router,
    render: app => app(App)
}).$mount('#app');
