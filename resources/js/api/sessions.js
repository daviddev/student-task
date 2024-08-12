import { $http } from './api.js';

export default {
    index(params) {
        return $http().get('api/sessions', {params});
    },
    store(data) {
        return $http().post('api/sessions', data);
    },
    update(id, data) {
        return $http().put(`api/sessions/${id}`, data);
    },
}
