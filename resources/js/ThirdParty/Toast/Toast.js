import { createToast } from 'mosha-vue-toastify';

export default class Toast {
    static base(message, type, timeout) {
        createToast(message, {
            type,
            showIcon: true,
            timeout
        });
    }

    static success(message, timeout = 2000) {
        Toast.base(message, 'success', timeout);
    }

    static info(message, timeout = 2000) {
        Toast.base(message, 'info', timeout);
    }

    static danger(message, timeout = 2000) {
        Toast.base(message, 'danger', timeout);
    }

    static warning(message, timeout = 2000) {
        Toast.base(message, 'warning', timeout);
    }
}
