<template>
    <Modal width="mid">
        <template v-slot:header>
            <div class="header">
                <h3>
                    忘記密碼
                </h3>
                <i class="icon-cancel" @click="$emit('close')"></i>
            </div>
        </template>
        <template v-slot:body>
            <p>請輸入圈圈帳號信箱，送出後約在 1 ～ 2 分鐘會將重置密碼信寄送到您的 Email 信箱內喔！</p>
            <div class="user-input-container">
                <div class="input-wrapper">
                    <div class="user-input">
                        <i class="icon-user"></i>
                        <input type="email" placeholder="信箱" v-model="email" @change="checkEmailExists">
                        <span class="error-message" v-if="errors.email">{{ errors.email }}</span>
                    </div>
                </div>
                <input type="button" value="寄送忘記密碼信" class="send" @click="submit" :disabled="! isValid">
            </div>
        </template>
    </Modal>
</template>

<script>
import { emailExists } from '@/APIs/User';
import Modal from '@/Components/Base/Modal';
import { postOptions } from '@/Components/Popups/UserAuth/postOptions';
import { validateEmail } from '@/Helper/Validator';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: 'ForgetPassword',
    components: { Modal },
    data() {
        return {
            email: '',
            errors: { email: null },
        };
    },
    methods: {
        checkEmailExists() {
            if (!validateEmail(this.email)) {
                this.errors.email = 'Email 格式不正確';
                return;
            }

            emailExists(this.email)
                .then(data => {
                    this.errors.email = data.exists ? null : '不存在此帳號';
                });
        },
        submit() {
            Inertia.post(
                this.route('user:forget-password'),
                { email: this.email },
                postOptions(this, '重置密碼信已經寄出，請前往信箱收信喔！ 🙂'),
            );
        },
    },
    computed: {
        isValid() {
            return validateEmail(this.email) && !this.errors.email;
        },
    },
};
</script>
<style lang="scss" scoped>
@import "@css/variables";
@import "./InputStyle";

p {
    color: $cGray666;
    font-size: 16px;
}

.header {
    position: relative;

    i {
        position: absolute;
        right: 5px;
        top: 5px;
        cursor: pointer;
    }
}
</style>
