<template>
    <Modal>
        <template v-slot:header>
            <time>{{ date }}</time>
        </template>
        <template v-slot:body>
            <div class="enterData">
                <div class="dataTitle">體重</div>
                <div class="dataInfo">
                    <input type="number" class="weight" v-model="amounts.weight">
                    <span>kg</span>
                </div>
            </div>
            <div class="enterData">
                <div class="dataTitle">體脂</div>
                <div class="dataInfo">
                    <input type="number" class="fat" v-model="amounts.fat">
                    <span>％</span>
                </div>
            </div>
        </template>
        <template v-slot:footer>
            <a href="#" @click.prevent="$emit('close')">關閉</a>
            <input type="button"
                   :disabled="!isValid"
                   value="紀錄"
                   class="send"
                   @click="save"
            >
        </template>
    </Modal>
</template>

<script>
import { getWeightState } from '@/APIs/WeightState';
import Modal from '@/Components/Base/Modal';
import { submit } from '@/Components/Popups/shares/shares';
import { computed, reactive } from 'vue';

export default {
    name: 'WeightAmountInput',
    components: { Modal },
    props: { date: Object },
    setup(props, context) {
        const amounts = reactive({ weight: 0, fat: 0 });
        const { date } = props;

        getWeightState(date.date)
            .then(({ data }) => {
                amounts.weight = data.weight;
                amounts.fat = data.fat;
            });

        const isValid = computed(() => amounts.weight > 20 && amounts.weight < 200);
        const save = () => submit(amounts, date.date, context);

        return { date: date.display, amounts, save, isValid };
    },
};
</script>
<style lang="scss" scoped>
@import "@/Components/Popups/shares/css/InputStyle";
</style>
