<template>
    <AccountBase menu="change-password" only>
        <template v-slot:body>
            <div class="stack"><h3 class="title">變更你的密碼</h3></div>
            <div class="stack">
                <div class="icon"><i class="icon-lock"></i></div>
                <div class="input-box">
                    <input type="password" placeholder="舊密碼" v-model="old_password">
                    <span class="error-message" v-if="errors.old_password">{{ errors.old_password }}</span>
                </div>
            </div>
            <div class="stack">
                <div class="icon"><i class="icon-lock"></i></div>
                <div class="input-box">
                    <input type="password" placeholder="新密碼" v-model="password">
                    <span class="error-message" v-if="errors.password">{{ errors.password }}</span>
                </div>
            </div>
            <div class="stack">
                <div class="icon"><i class="icon-lock"></i></div>
                <div class="input-box">
                    <input type="password" placeholder="再次輸入新密碼" v-model="password_confirmation">
                    <span class="error-message" v-if="errors.password_confirmation">{{ errors.password_confirmation }}</span>
                </div>
            </div>
            <div>
            </div>
            <div class="stack">
                <input type="button" value="更新" @click="submit" class="send" :disabled="! isValid">
            </div>
        </template>
    </AccountBase>
</template>

<script>
import Layout from '@/Layouts/Layout';
import AccountBase from '@/Pages/AccountBase';
import Toast from '@/ThirdParty/Toast/Toast';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: 'ChangePassword',
    layout: Layout,
    components: { AccountBase },
    data() {
        return {
            old_password: '',
            password: '',
            password_confirmation: '',
            errors: {},
        };
    },
    methods: {
        resetInputs() {
            this.old_password = '';
            this.password = '';
            this.password_confirmation = '';
        },
        submit() {
            const payload = {
                old_password: this.old_password,
                password: this.password,
                password_confirmation: this.password_confirmation,
            };
            this.errors = {};
            Inertia.post(route('user:change-password'),
                payload,
                {
                    onError: (errors) => this.errors = errors,
                    onSuccess: () => {
                        this.resetInputs();
                        Toast.success('密碼變更成功囉！');
                    },
                },
            );
        },
    },
    computed: {
        userInfo() {
            return this.$page.props.auth.user;
        },
        isNewPasswordValid() {
            return (this.password && this.password_confirmation) &&
                this.password === this.password_confirmation;
        },
        isValid() {
            return this.old_password && this.isNewPasswordValid;
        },
    },
    watch: {
        isNewPasswordValid(val) {
            this.errors.password_confirmation = val === false ? '密碼內容不一致' : '';
        },
    },
    created() {
        if (!this.userInfo) {
            Inertia.get(route('home'));
        }
    },
};
</script>

<style scoped lang="scss">
@import "@css/variables";
@import "@css/mixins";

$width: 450px;
.w-main {
    padding-top: 5vh;

    .w-container {
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        width: $width;
        margin: 0 auto;
        padding: 20px;
        @include rwd(700px) {
            width: 100%;
        }

        .icon {
            margin-right: 5px;

            i {
                color: $cOrange;
                position: relative;
                top: 10px;
            }
        }

        .stack {
            margin-bottom: 15px;
            width: 100%;
            display: flex;
            justify-content: center;

            .input-box {
                width: 100%;
                position: relative;

                input, select {
                    &::placeholder, &.readonly {
                        color: $cGrayccc;
                    }

                    border-radius: 50px;
                    border-bottom: none;
                    padding: 10px 20px;
                }

                select {
                    appearance: none;
                }

                .w-select {
                    &:after {
                        position: absolute;
                        content: "";
                        width: 10px;
                        height: 10px;
                        right: 20px;
                        top: 18px;
                        background: url(https://chanchanweight.s3.ap-northeast-2.amazonaws.com/statics/icons/prevBtn.svg) no-repeat;
                        transform: rotate(270deg);
                    }
                }

                .birthday-box {
                    display: flex;
                    justify-content: space-between;

                    input {
                        font-size: 14px;
                        text-align: center;
                    }

                    p {
                        margin: 0 5px;
                    }
                }
            }

            input[type=button] {
                margin-top: 20px;
                width: 100%;
                font-size: 24px;
            }
        }


        .title {
            text-align: center;
            font-size: 24px;
        }

        .profile-circle {
            border-radius: 50%;
            overflow: hidden;
            width: 150px;
            height: 150px;
        }

    }


}

.error-message {
    font-size: 14px;
    color: $cOrange;
    padding-left: 5px;
}
</style>
