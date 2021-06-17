<template>
    <Modal>
        <template v-slot:header>
            <div class="header">
                <h3>
                    <slot name="title"></slot>
                </h3>
                <i class="icon-cancel" @click="$emit('close')"></i>
            </div>
        </template>
        <template v-slot:body>
            <slot></slot>
        </template>
        <template v-slot:footer>
            <div class="login-footer">
                <p>
                    <slot name="social-media-message"></slot>
                </p>
                <div class="social-media-login">
                    <div class="social-media-button google-button" @click="googleLogin">
                        <img src="https://chanchanweight.s3.ap-northeast-2.amazonaws.com/statics/icons/google.svg"
                             alt="google login">
                    </div>
                </div>
            </div>
        </template>
    </Modal>
</template>

<script>
import Modal from '@/Components/Base/Modal';
import { postOptions } from '@/Components/Popups/UserAuth/postOptions';
import FirebaseApp from '@/ThirdParty/Firebase/Firebase';
import { Inertia } from '@inertiajs/inertia';

export default {
    name: 'BaseAuthPanel',
    components: { Modal },
    data() {
        return {
            errors: {},
        };
    },
    methods: {
        googleLogin() {
            (new FirebaseApp()).loginWithGoogle().then(data => {
                const { user: { uid } } = data;
                Inertia.post(
                    '/weight/authWithFirebase',
                    { uid },
                    postOptions(this, '登入成功囉！ 🙂')
                );
            });
        },
    },
};
</script>
<style lang="scss" scoped>
@import "@css/variables";

p {
    text-align: center;
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

.line-deco {
    content: "";
    width: 23%;
    height: 1px;
    background: lightgrey;
    position: absolute;
    border-radius: 50%;
    top: 47%;
}


.login-footer {
    p {
        &:before {
            @extend .line-deco;
            left: 0;
        }

        &:after {
            @extend .line-deco;
            right: 0;
        }
    }

    .social-media-button {
        border-radius: 15px;
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .google-button {
        background: $cGrayeee;
    }
}
</style>
