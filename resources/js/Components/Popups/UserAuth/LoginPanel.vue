<template>
    <BaseAuthPanel>
        <template v-slot:title>
            登入
        </template>
        <template v-slot:social-media-message>
            使用社群帳號登入
        </template>

        <p>使用圈圈帳號登入</p>
        <div class="user-input-container">
            <div class="input-wrapper">
                <div class="user-input">
                    <i class="icon-user"></i>
                    <input type="email" placeholder="信箱" v-model="email">
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
            <input type="button" value="登入" class="send" @click="login" :disabled="! isValid">
            <div class="extra-links">
                <a href="#" @click.prevent="$emit('openPanel', {from: 'login', to:'forgetPassword'})">忘記密碼</a>
                <span> | </span>
                <a href="#" @click.prevent="$emit('openPanel', {from: 'login', to:'register'})">會員註冊</a>
            </div>
        </div>
    </BaseAuthPanel>
</template>

<script>
import BaseAuthPanel from '@/Components/Popups/UserAuth/BaseAuthPanel';
import { postOptions } from '@/Components/Popups/UserAuth/postOptions';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: 'LoginPanel',
    components: { BaseAuthPanel },
    data() {
        return {
            email: '',
            password: '',
            errors: {},
        };
    },
    computed:{
      isValid(){
          return this.email && this.password;
      }
    },
    methods: {
        login() {
            this.errors = {};
            Inertia.post(this.route('login'),
                { email: this.email, password: this.password },
                postOptions(this, '登入成功囉！ 🙂')
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

.extra-links {
    text-align: center;
}
</style>
