import VueRouter from 'vue-router';
import store from '../store/index.js';

import Login from '@/components/auth/login';
import Students from '@/components/views/students';
import Sessions from '@/components/views/sessions';

const routes = [
    {
        name: 'Students',
        path: '/',
        component: Students,
    },
    {
        name: 'Sessions',
        path: '/sessions',
        component: Sessions,
    },
    {
        name: 'Login',
        path: '/login',
        component: Login,
    },
];

const router = new VueRouter({
    routes,
    linkExactActiveClass: 'active',
    mode: 'history'
});

router.beforeEach(async(to, from, next) => {
    if (to.name !== 'Login' && !store.getters.user) next({ name: 'Login' });
    else if (store.getters.user && to.name === 'Login') next({ name: 'Students' });
    else next()
});

export default router;
