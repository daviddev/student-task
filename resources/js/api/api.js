import axios from 'axios';
import store from '../store/index.js';
import convertKeys from '@/helpers/convert-keys';

export const $http = (useConvertKeys = true, isFormData = false) => {
    const instance = axios.create();
    const contentType = isFormData ? 'multipart/form-data' : 'application/json';

    instance.defaults.headers['common']['Accept'] = 'application/json';
    instance.defaults.headers['common']['Authorization'] = 'Bearer ' + store.getters.token;
    instance.defaults.headers['common']['Content-Type'] = contentType;

    if (useConvertKeys) {
        // convert to snakeCase when browser request
        instance.interceptors.request.use(request => {
            request.params = convertKeys.toSnake(request.params);
            request.data = convertKeys.toSnake(request.data);

            return request;
        })

        // convert to camelcase when browser receive
        instance.interceptors.response.use(response => {
            response.body = convertKeys.toCamel(response.body);
            response.data = convertKeys.toCamel(response.data);

            return response;
        })
    }

    return instance;
};
