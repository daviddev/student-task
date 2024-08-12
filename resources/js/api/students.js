import { $http } from './api.js';

export default {
    index(params) {
        return $http().get('api/students', {params});
    },
    store(data) {
        const axiosInstance = $http(false);

        return axiosInstance.post('api/students', data);
    },
    importData(id, data) {
        const axiosInstance = $http(false, true);

        return axiosInstance.post(`api/students/${id}/import-data`, data);
    },
    generateReport(id, data) {
        return $http().post(`api/students/${id}/generate-report`, data, { responseType: 'blob' });
    },
}
