<template>
    <div class="menus">
        <inertia-link :href="route('account')" method="get" as="a"
                      :class="{'active': isActive('update-account')}"
        >
            更新資料
        </inertia-link>
        <inertia-link :href="route('change-password')" method="get" as="a"
                      :class="{'active': isActive('change-password')}"
        >
            變更密碼
        </inertia-link>
        {{ auth }}
    </div>
</template>

<script>
export default {
    name: 'AccountMenu',
    props: {
        auth: Object,
        selected: {
            type: String,
            default: 'update-account'
        }
    },
    methods:{
        isActive(selected){
            return this.selected === selected;
        }
    }
};
</script>

<style scoped lang="scss">
@import "@css/variables";
.menus {
    margin: 20px auto;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;

    a {
        display: block;
        width: 100px;
        border: 1px solid $cOrange;
        background: none;
        color: $cOrange;
        padding: 0px;
        text-align: center;
        border-radius: 40px;
        margin: 10px;
        cursor: pointer;
        transition: all 0.3s ease;

        &.active {
            background: $cBlue;
            color: white;
            border: 1px solid $cBlue;
        }
    }
}
</style>
