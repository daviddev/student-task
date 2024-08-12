import Vue from 'vue';
import Vuex from 'vuex';

import createPersistedState from 'vuex-persistedstate';
import Cookies from 'js-cookie';

import user from './modules/user.js';

Vue.use(Vuex);
createPersistedState({storage: window.sessionStorage});

const store = new Vuex.Store({
    modules: { user },
    plugins: [
        createPersistedState({
            storage: {
                getItem: (key) => Cookies.get(key),
                setItem: (key, value) => Cookies.set(key, value, {
                    expires: 3,
                }),
                removeItem: (key) => Cookies.remove(key)
            },
            paths: ['user']
        })
    ]
});

export default store;
