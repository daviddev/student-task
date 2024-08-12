import { Notification } from 'element-ui';
import _ from 'lodash';

export default {
    methods: {
        handleMessage(type, response) {
            let message;
            if (_.get(response, 'response.data.errors')) {
                message = Object.values(_.get(response, 'response.data.errors'));
            } else {
                message = _.get(response, 'data.message') || _.get(response, 'response.data.message') || _.get(response, 'message');
            }

            if (_.isArray(message)) {
                message = message.join('<br/>');
            }

            Notification({
                message: message,
                type: type,
                title: _.capitalize(type),
                dangerouslyUseHTMLString: true
            });
        }
    }
}
