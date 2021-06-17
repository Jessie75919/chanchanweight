<template>
    <div class="w-main">
        <AccountMenu v-if="! hideMenu" :selected="menu"/>
        <div class="w-container">
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script>
import AccountMenu from '@/Components/Weight/AccountMenu';
import Layout from '@/Layouts/Layout';

export default {
    name: 'AccountBase',
    layout: Layout,
    components: {
        AccountMenu,
    },
    props: {
        menu: String,
        hideMenu: {
            type: Boolean,
            default: false
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
</style>
