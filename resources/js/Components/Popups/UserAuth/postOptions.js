import Toast from '@/ThirdParty/Toast/Toast';

export const postOptions = function(vm, message){
    return {
        onError: (errors) => vm.errors = errors,
        onSuccess: () => {
            vm.$emit('close');
            Toast.success(message);
        },
    };
};
