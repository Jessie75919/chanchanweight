<template>
    <div>
        <header>
            <figure>
                <img src="https://chanchanweight.s3.ap-northeast-2.amazonaws.com/statics/icons/logo.svg" alt="圈圈體重計"
                     title="圈圈・量量"/>
            </figure>
            <h1>圈圈・量量</h1>
        </header>
        <main class="main-content">
            <slot/>
        </main>
        <nav>
            <div class="menu">
                <div class="menuList">
                    <inertia-link :href="route('home')" method="get" as="a">
                        <i class="icon-home"></i>首頁
                    </inertia-link>
                </div>
                <div class="menuList">
                    <inertia-link :href="route('calendar')" method="get" as="a">
                        <i class="icon-calendar"></i>日曆
                    </inertia-link>
                </div>
                <div class="menuList">
                    <inertia-link :href="route('chart')" method="get" as="a">
                        <i class="icon-presentation"></i>圖表
                    </inertia-link>
                </div>
                <template v-if="$page.props.auth.user">
                    <div class="menuList">
                        <inertia-link :href="route('account')" method="get" as="a">
                            <i class="icon-user"></i>帳戶
                        </inertia-link>
                    </div>
                    <div class="menuList">
                        <inertia-link :href="route('user:logout')" method="post" as="a">
                            <i class="icon-logout"></i>登出
                        </inertia-link>
                    </div>
                </template>
                <div class="menuList" v-else>
                    <a href="#" @click.prevent="isOpen.loginPanel = true">
                        <i class="icon-login"></i>登入
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <transition name="fade">
        <LoginPanel v-if="isOpen.loginPanel"
                    @close="isOpen.loginPanel = false"
                    @openPanel="openPanel"
        />
    </transition>
    <transition name="fade">
        <RegisterPanel v-if="isOpen.registerPanel"
                       @close="isOpen.registerPanel = false"
                       @openPanel="openPanel"
        />
    </transition>
    <transition name="fade">
        <ForgetPassword v-if="isOpen.forgetPasswordPanel"
                        @close="isOpen.forgetPasswordPanel = false"
                        @openPanel="openPanel"
        />
    </transition>

</template>

<script>
import ForgetPassword from '@/Components/Popups/UserAuth/ForgetPassword';
import LoginPanel from '@/Components/Popups/UserAuth/LoginPanel';
import RegisterPanel from '@/Components/Popups/UserAuth/RegisterPanel';
import { reactive } from 'vue';

export default {
    name: 'Layout',
    components: { LoginPanel, RegisterPanel, ForgetPassword },
    setup() {
        const isOpen = reactive({
            loginPanel: false,
            registerPanel: false,
            forgetPasswordPanel: false,
        });
        return { isOpen };
    },
    methods: {
        openPanel({ from, to }) {
            this.isOpen[from + 'Panel'] = false;
            this.isOpen[to + 'Panel'] = true;
        },
    },
    watch: {
        shouldLogin: {
            handler: function(val) {
                if (val === true) {
                    this.isOpen.loginPanel = true;
                }
            },
            deep: true,
            immediate: true,
        },
        'isOpen.loginPanel': {
            handler: function(val) {
                if (val === false) {
                    this.$page.props.shouldLogin = false;
                }
            },
            deep: true,
            immediate: true,
        },
    },
    computed: {
        shouldLogin() {
            return this.$page.props.shouldLogin;
        },
    },
};
</script>

<style lang="scss">
@import "@css/mixins";
.main-content {
    padding: 10vh 3%;
    min-height: 100vh;
    @include rwd(400px) {
        padding: 15vh 3%;
    }
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
