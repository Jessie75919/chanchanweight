<template>
    <AccountBase hide-menu>
        <template v-slot:body>
            <div class="stack"><h3 class="title">請輸入新的密碼</h3></div>
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
                    <span class="error-message" v-if="errors.password_confirmation">
                        {{ errors.password_confirmation }}
                    </span>
                    <span class="error-message" v-if="errors.email">{{ errors.email }}</span>
                </div>
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
    name: 'ResetPassword',
    layout: Layout,
    components: { AccountBase },
    data() {
        return {
            password: '',
            password_confirmation: '',
            errors: {},
        };
    },
    props: {
        auth: Object,
        email: String,
        token: String,
    },
    methods: {
        resetInputs() {
            this.password = '';
            this.password_confirmation = '';
        },
        submit() {
            const payload = {
                token: this.token,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            };
            this.errors = {};
            Inertia.post(route('user:reset-password'),
                payload,
                {
                    onError: (errors) => this.errors = errors,
                    onSuccess: () => {
                        this.resetInputs();
                        Toast.success('密碼變更成功囉！請重新登入！');
                    },
                },
            );
        },
    },
    computed: {
        isValid() {
            return (this.password && this.password_confirmation) &&
                this.password === this.password_confirmation;
        },
    },
    watch: {
        isValid(val) {
            this.errors.password_confirmation = (val === false) ? '密碼內容不一致' : '';
        },
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
