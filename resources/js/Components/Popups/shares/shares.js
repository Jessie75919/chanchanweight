import Toast from '@/ThirdParty/Toast/Toast';
import { Inertia } from '@inertiajs/inertia';

export const submit = (amount, date, context) => {
    const { weight, fat, temperature } = amount;
    Inertia.post('/weight/store/amounts',
        { weight, fat, temperature, date },
        {
            onSuccess: () => {
                if(context){
                    context.emit('close');
                }
                Toast.success('記錄存檔成功囉！');
            },
        },
    );
};
