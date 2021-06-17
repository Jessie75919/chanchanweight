<template>
    <transition name="fade">
        <div class="popup weightPopup">
            <div :class="'popupContainer '+ width">
                <div class="modal-header">
                    <slot name="header"></slot>
                </div>
                <slot name="body"></slot>
                <div class="popupBtn">
                    <slot name="footer">
                    </slot>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'Modal',
    props: {
        width: {
            type: String,
            default: '',
        },
    },
    setup(props) {
        const { width } = props;
        return { width };
    },
};
</script>
<style lang="scss">
@import './resources/css/variables';

.popup {
    position: fixed;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    top: 0;
    left: 0;
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-content: center;
    align-items: center;

    .popupContainer {
        width: 300px;
        background-color: $cWhite;
        padding: 20px;
        border-radius: 20px;

        &.mid {
            width: 540px;
        }

        &.wide {
            width: 900px;
        }

        .modal-header {
            font-size: 0.9em;
            text-align: center;
        }

        .popupBtn {
            padding-top: 30px;
            text-align: center;

            a {
                display: inline-block;
                background: $cGrayccc;
                color: $cWhite;
                width: 120px;
                border-radius: 50px;
                text-align: center;
                padding: 10px 0;
                margin: 5px 2px;

                &:hover {
                    background-color: $cBlue;
                }
            }
        }
    }
}

</style>
