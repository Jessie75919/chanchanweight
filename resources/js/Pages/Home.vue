<template>
        <div class="indexRecord">
            <div class="todayBtn">
                <a href="#" @click="goToToday" :class="{ disabled: isToday }">回今天</a>
            </div>
            <div class="timePicker slider">
                <time v-for="date in dates" :key="date.date">{{ date.display }}</time>
            </div>
            <div class="recordArea">
                <MainMenu title="體重體脂" img-url="weight.svg" @click="openModal('weightAmountInput')"/>
                <MainMenu title="體溫" img-url="temperature.svg" @click="openModal('temperatureInput')"/>
                <MainMenu title="身體狀態" img-url="status.svg" @click="openModal('bodyStatusInput')"/>
            </div>
        </div>

        <transition name="fade">
            <WeightAmountInput v-if="isOpen.weightAmountInput"
                               :date="currentDate"
                               @close="isOpen.weightAmountInput = false"
            />
        </transition>
        <transition name="fade">
            <TemperatureInput v-if="isOpen.temperatureInput"
                              :date="currentDate"
                              @close="isOpen.temperatureInput = false"
            />
        </transition>
        <transition name="fade">
            <BodyStatusInput v-if="isOpen.bodyStatusInput"
                             :date="currentDate"
                             @close="isOpen.bodyStatusInput = false"
            />
        </transition>
</template>

<script>
import BodyStatusInput from '@/Components/Popups/BodyStatusInput';
import TemperatureInput from '@/Components/Popups/TemperatureInput';
import WeightAmountInput from '@/Components/Popups/WeightAmountInput';
import MainMenu from '@/Components/Weight/MainMenu';
import Layout from '@/Layouts/Layout';
import { usePage } from '@inertiajs/inertia-vue3';
import $ from 'jquery';
import 'slick-carousel';
import { computed, inject, onMounted, reactive, ref } from 'vue';

const goToSlick = (idx) => $('.timePicker')[0].slick.slickGoTo(idx);
const generateDates = () => {
    const dayjs = inject('dayjs');
    const format = (dayjsInstance) => ({
        date: dayjsInstance.format('YYYY-MM-DD'),
        display: dayjsInstance.format('YYYY / MM / DD ddd'),
    });

    return Array.from(Array(5).keys())
        .reverse()
        .map((num) => format(dayjs().subtract(num, 'days')));
};
export default {
    name: 'Home',
    layout: Layout,
    components: {
        MainMenu,
        WeightAmountInput,
        TemperatureInput,
        BodyStatusInput,
    },
    setup() {
        const dates = generateDates();
        const isOpen = reactive({
            weightAmountInput: false,
            temperatureInput: false,
            bodyStatusInput: false,
        });
        const todayIndex = 4;
        const currentIndex = ref(todayIndex);
        const currentDate = computed(() => dates[currentIndex.value]);
        const isToday = computed(() => todayIndex === currentIndex.value);
        const goToToday = () => goToSlick(dates.length - 1);

        const initSlick = () => {
            const timePicker = $('.timePicker');
            timePicker.slick({
                infinite: false,
                dots: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                initialSlide: 4,
            });
            timePicker.on('afterChange', function(event, slick, currentSlide, nextSlide) {
                currentIndex.value = currentSlide;
            });
        };

        onMounted(() => initSlick());
        const user = computed(() => usePage().props.value.auth.user);
        const openModal = (modalName) => {
            !user.value
                ? usePage().props.value.shouldLogin = true
                : isOpen[modalName] = true;
        };

        return { dates, goToToday, openModal, isOpen, currentDate, currentIndex, isToday };
    },
};
</script>

<style lang="scss">
@import "@css/variables";
.indexRecord .todayBtn a {
    &.disabled {
        background: $cGrayeee;
    }
}
</style>
