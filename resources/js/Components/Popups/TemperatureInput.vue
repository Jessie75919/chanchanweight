<template>
    <Modal>
        <template v-slot:header>
            <time>{{ date.display }}</time>
        </template>
        <template v-slot:body>
            <div class="enterData">
                <div class="dataTitle">體溫</div>
                <div class="dataInfo">
                    <input type="number" class="temperature" v-model.number="temperature">
                    <span>℃</span>
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
import { computed, ref } from 'vue';

export default {
    name: 'TemperatureInput',
    components: { Modal },
    props: { date: Object },
    setup(props, context) {
        const { date } = props;
        const temperature = ref(0);
        getWeightState(date.date).then(({ data }) => {
            temperature.value = data.temperature;
        });
        const isValid = computed(() => temperature.value > 35 && temperature.value <= 42);
        const save = () => submit({ temperature: temperature.value }, date.date, context);

        return { date, temperature, save, isValid };
    },
};
</script>
<style lang="scss" scoped>
@import "@/Components/Popups/shares/css/InputStyle";
</style>
