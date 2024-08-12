export default {
    state: {
        user: null,
        token: null
    },
    mutations: {
        setUser(state, payload) {
            state.user = payload;
        },
        setToken(state, payload) {
            state.token = payload;
        },
    },
    getters: {
        user(state) {
            return state.user;
        },
        token(state) {
            return state.token;
        },
    }
};
