<template>
    <BaseAuthPanel>
        <template v-slot:title>
            註冊
        </template>
        <template v-slot:social-media-message>
            使用社群帳號註冊
        </template>
        <div class="user-input-container">
            <div class="input-wrapper">
                <div class="user-input">
                    <i class="icon-user"></i>
                    <input type="email" placeholder="信箱"
                           @change="isEmailExists"
                           v-model="email">
                    <span class="error-message" v-if="errors.email">{{ errors.email }}</span>
                </div>
            </div>
            <div class="input-wrapper">
                <div class="user-input">
                    <i class="icon-lock"></i>
                    <input type="password" placeholder="密碼" v-model="password">
                    <span class="error-message" v-if="errors.password">{{ errors.password }}</span>
                </div>
            </div>
            <span class="reminder-message">
                小提醒：密碼需要至少 8 碼喔！
            </span>
            <div class="input-wrapper">
                <div class="user-input">
                    <i class="icon-lock"></i>
                    <input type="password" placeholder="再次輸入密碼" v-model="password_confirmation">
                    <span class="error-message" v-if="errors.password_confirmation">{{
                            errors.password_confirmation
                        }}</span>
                </div>
            </div>
            <input type="button" value="註冊" class="send" @click="register">

            <div class="extra-links">
                已經有帳號了？
                <a href="#" @click.prevent="$emit('openPanel', {from: 'register', to: 'login'})">馬上登入</a>
            </div>
        </div>
    </BaseAuthPanel>
</template>

<script>
import { emailExists } from '@/APIs/User';
import BaseAuthPanel from '@/Components/Popups/UserAuth/BaseAuthPanel';
import { postOptions } from '@/Components/Popups/UserAuth/postOptions';
import { validateEmail } from '@/Helper/Validator';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: 'RegisterPanel',
    components: { BaseAuthPanel },
    data() {
        return {
            email: '',
            password: '',
            password_confirmation: '',
            errors: {},
            emailExists: false,
        };
    },
    computed: {
        isValid() {
            return this.email && this.password && this.isPasswordConfirmationValid;
        },
        isPasswordConfirmationValid() {
            return this.password === this.password_confirmation;
        },
    },
    methods: {
        checkValidationError() {
            let hasError = false;
            if (!this.email) {
                this.errors.email = '信箱 欄位必須填寫';
                hasError = true;
            }

            if (this.email && !validateEmail(this.email)) {
                this.errors.email = 'Email 格式不正確';
                hasError = true;
            }
            if (this.email && this.emailExists) {
                this.errors.email = '此信箱已經被使用過了!';
                hasError = true;
            }

            if (!this.password) {
                this.errors.password = '密碼 欄位必須填寫';
                hasError = true;
            }

            if (this.password && this.password.length < 8) {
                this.errors.password = '密碼 至少需要 8 碼';
                hasError = true;
            }

            if (!this.isPasswordConfirmationValid) {
                this.errors.password = '密碼輸入內容不一致';
                hasError = true;
            }
            return !hasError;
        },
        isEmailExists() {
            if (!validateEmail(this.email)) {
                return;
            }
            emailExists(this.email)
                .then(data => {
                    if (data.exists) {
                        this.errors.email = '此信箱已經被使用過了!';
                        this.emailExists = true;
                    } else {
                        this.errors.email = '';
                        this.emailExists = false;
                    }
                });
        },
        register() {
            this.errors = {};
            if (!this.checkValidationError()) {
                return;
            }
            Inertia.post(
                this.route('weight:register'),
                {
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                },
                postOptions(this, '註冊成功囉！ 🙂'),
            );
        },
    },
};
</script>
<style lang="scss" scoped>
@import "./InputStyle";
@import "@css/variables";

p {
    text-align: center;
}

.reminder-message {
    @extend .error-message;
}

.extra-links {
    margin-top: 10px;
    text-align: center;
}
</style>
