import { $http } from './api.js';

export default {
    update(data) {
        return $http().patch(`api/users`, data);
    },
}
