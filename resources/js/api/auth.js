import { $http } from './api.js';

export default {
    login(data) {
        return $http().post('api/auth/login', data);
    },
    logout() {
        return $http().post('api/auth/logout', {});
    }
}
