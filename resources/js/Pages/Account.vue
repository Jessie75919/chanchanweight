<template>
    <AccountBase menu="update-account">
        <template v-slot:body>
            <div class="stack"><h3 class="title">基本資料</h3></div>
            <div class="stack">
                <div class="profile-circle">
                    <img
                        :src="'https://chanchanweight.s3.ap-northeast-2.amazonaws.com/statics/icons/' + imageFile"
                        alt="profile">
                </div>
            </div>
            <div class="stack">
                <div class="icon">
                    <i class="icon-email"></i>
                </div>
                <div class="input-box">
                    <input type="email" readonly
                           v-model="email"
                           class="readonly"
                    >
                </div>
            </div>
            <div class="stack">
                <div class="icon">
                    <i class="icon-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" placeholder="姓名" v-model="name">
                </div>
            </div>
            <div class="stack">
                <div class="icon">
                    <i class="icon-heart"></i>
                </div>
                <div class="input-box">
                    <div class="w-select">
                        <select name="gender" id="gender" v-model="gender">
                            <option value="M">男</option>
                            <option value="F">女</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="stack">
                <div class="icon">
                    <i class="icon-birthday"></i>
                </div>
                <div class="input-box">
                    <div class="birthday-box">
                        <input type="number" v-model="birthday.year" min="1911">
                        <p>年</p>
                        <input type="number" v-model="birthday.month" min="1" max="12">
                        <p>月</p>
                        <input type="number" v-model="birthday.date" min="1" max="31">
                        <p>日</p>
                    </div>
                    <span class="error-message" v-if="! isBirthdayValid">生日格式不完整或不正確！</span>
                </div>
            </div>
            <div class="stack">
                <input type="button" value="更新"
                       @click="submit"
                       class="send"
                       :disabled="! isValid"
                >
            </div>
        </template>
    </AccountBase>
</template>

<script>
import AccountMenu from '@/Components/Weight/AccountMenu';
import Layout from '@/Layouts/Layout';
import AccountBase from '@/Pages/AccountBase';
import Toast from '@/ThirdParty/Toast/Toast';
import { Inertia } from '@inertiajs/inertia';
import { inject } from 'vue';

export default {
    name: 'Account',
    layout: Layout,
    components: {
        AccountMenu,
        AccountBase,
    },
    data() {
        const dayjs = inject('dayjs');
        return {
            today: dayjs(),
            email: '',
            name: '',
            birthday: { year: '', month: '', date: '' },
            gender: 'M',
        };
    },
    methods: {
        submit() {
            if (!this.isValid) {
                return;
            }
            const payload = {
                name: this.name,
                birthday: Object.values(this.birthday).join('-').replace('--', ''),
                gender: this.gender,
            };
            Inertia.post(route('user:update'),
                payload,
                {
                    onError: (errors) => {
                        console.log(errors);
                    },
                    onSuccess: () => {
                        Toast.success('更新資料成功囉！');
                    },
                },
            );
        },
    },
    computed: {
        isBirthdayValid() {
            const { year, month, date } = this.birthday;
            if ((!year && !month && !date)) {
                return true;
            }
            const required = year && month && date;
            const yearValid = (year <= this.today.year() && year > 1911);
            const monthValid = (month <= 12 && month >= 1);
            const dateValid = (date <= 31 && date >= 1);
            return required && yearValid && monthValid && dateValid;
        },
        imageFile() {
            return (this.gender === 'M' ? 'boy' : 'girl') + '.svg';
        },
        userInfo() {
            return this.$page.props.auth.user;
        },
        isValid() {
            return this.userInfo && this.isBirthdayValid;
        },
    },
    created() {
        if (!this.userInfo) {
            Inertia.get(route('home'));
            return;
        }
        this.email = this.userInfo.email;
        this.gender = this.userInfo.gender || 'M';
        this.name = this.userInfo.name;
        if (!this.userInfo.birth) {
            return;
        }
        const dayjs = inject('dayjs');
        const birthday = dayjs(this.userInfo.birth);
        this.birthday.year = birthday.year();
        this.birthday.month = birthday.month() + 1;
        this.birthday.date = birthday.date();
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
</style>
