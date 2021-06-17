<template>
    <div class="w-main">
        <div class="menus">
            <div class="menu"
                 :class="{active: menu.val === currentMenu.val}"
                 v-for="menu in menus" :key="menu.val"
                 @click="currentMenu = menu"
            >
                {{ menu.name }}
            </div>
        </div>
        <div class="date-selector">
            <div>
                <i class="icon-leftarrow" @click="preMonth"></i>
            </div>
            <div class="date-display">
                {{ currentMonth.displayDate }}
            </div>
            <div>
                <i class="icon-rightarrow"
                   :class="{disabled: canNotNextMonth}"
                   @click="nextMonth"></i>
            </div>
        </div>
        <div class="w-container">
            <div class="chart-container">
                <vue3-chart-js v-if="lineChart" v-bind="{...lineChart}" ref="chartRef"/>
            </div>
        </div>
    </div>
</template>

<script>
import { weightStatesByMonth } from '@/APIs/WeightState';
import Layout from '@/Layouts/Layout';
import Vue3ChartJs from '@j-t-mcc/vue3-chartjs';
import zoomPlugin from 'chartjs-plugin-zoom';
import { inject } from 'vue';

Vue3ChartJs.registerGlobalPlugins([zoomPlugin]);

const options = {
    plugins: {
//        zoom: {
//            zoom: {
//                wheel: { enabled: true },
//                pinch: { enabled: true },
//                mode: 'x',
//            },
//        },
//        datalabels: {
//            backgroundColor: (context) => context.dataset.backgroundColor,
//            borderRadius: 20,
//            color: 'white',
//            padding: 3,
//        },
    },
};
export default {
    name: 'Chart',
    layout: Layout,
    components: { Vue3ChartJs },
    data() {
        const dayjs = inject('dayjs');
        const curDayjs = dayjs();
        const menus = [
            { name: '體重', val: 'weight', dataKey: 'weightData' },
            { name: '體脂', val: 'fat', dataKey: 'fatData' },
            { name: '體溫', val: 'temperature', dataKey: 'temperatureData' },
        ];
        return {
            curDayjs,
            todayMonth: dayjs().format('YYYY-MM'),
            currentMonth: {
                date: curDayjs.format('YYYY-MM'),
                displayDate: curDayjs.format('YYYY 年 MM 月'),
            },
            weightData: [],
            fatData: [],
            temperatureData: [],
            payloadData: [],
            lineChart: null,
            menus,
            currentMenu: menus[0],
        };
    },
    setup() {
        const getWeightStatesByMonth = (year, month) =>
            weightStatesByMonth({ year, month })
                .then(({ data }) => data);

        return { getWeightStatesByMonth };
    },
    methods: {
        updateMonth(mode, num = 1) {
            this.curDayjs = this.curDayjs[mode](num, 'month');
            this.currentMonth.date = this.curDayjs.format('YYYY-MM');
            this.currentMonth.displayDate = this.curDayjs.format('YYYY 年 MM 月');
        },
        nextMonth() {
            if (this.canNotNextMonth) {
                return;
            }
            this.updateMonth('add');
        },
        preMonth() {
            this.updateMonth('subtract');
        },
        renderLineChart() {
            this.lineChart ? this.updateChart() : this.initChart();
        },
        async onChangeMonth() {
            await this.loadPage();
            this.renderLineChart();
        },
        onChangeMenu(menu) {
            this.payloadData = this[menu.dataKey];
            this.renderLineChart();
        },
        updateChart() {
//            this.lineChart.options.plugins.title = {
//                text: this.currentMenu.name,
//                display: true,
//            };
            this.lineChart.data.labels = this.monthDays;
            this.lineChart.data.datasets = [this.datasetsConfig()];

            this.$refs.chartRef.update(200);
        },
        resetAmountData() {
            this.payloadData = [];
            this.weightData = [];
            this.fatData = [];
            this.temperatureData = [];
        },
        updateAmountData(data) {
            this.resetAmountData();
            const pivot = data.reduce((acc, cur) => {
                const { date, weight, fat, temperature } = cur;
                const day = parseInt(date.split('-')[2], 10);
                acc[day] = { weight, fat, temperature };
                return acc;
            }, {});

            this.monthDays.forEach((day) => {
                const item = pivot[day] || {};
                const { weight, fat, temperature } = item;
                this.weightData.push(weight || null);
                this.fatData.push(fat || null);
                this.temperatureData.push(temperature || null);
            });
        },
        datasetsConfig(){
          return  {
              label: this.currentMenu.name,
              data: this.payloadData,
              backgroundColor: 'rgb(227, 150, 75)',
              borderColor: 'rgb(227, 150, 75)',
              borderWidth: 2,
              pointBackgroundColor: 'rgb(75, 186, 214)',
              pointBorderColor: 'rgb(75, 186, 214)',
              pointBorderWidth: 1,
              tension: 0.3,
              pointHoverBackgroundColor: 'rgb(227, 150, 75)',
          };
        },
        initChart() {
            this.lineChart = {
                type: 'line',
//                plugins: [dataLabels],
                data: {
                    labels: this.monthDays,
                    datasets: [this.datasetsConfig()],
                },
//                options,
            };
        },
        async loadPage() {
            console.log(this.isLogin);
            if(! this.isLogin){
                return;
            }
            const data = await this.getWeightStatesByMonth(this.curYear, this.curMonth);
            this.updateAmountData(data);
            this.payloadData = this[this.currentMenu.dataKey];
        },
    },
    computed: {
        isLogin() {
            return this.$page.props.auth.user;
        },
        curYear() {
            return this.curDayjs.year();
        },
        curMonth() {
            return this.curDayjs.month() + 1;
        },
        monthDays() {
            return Array.from(Array(this.curDayjs.daysInMonth()), (_, i) => i + 1);
        },
        canNotNextMonth() {
            return this.todayMonth === this.currentMonth.date;
        },
    },
    watch: {
        currentMenu: 'onChangeMenu',
        currentMonth: { handler: 'onChangeMonth', deep: true, immediate: true },
    },
    created() {
        if (!this.isLogin) {
            this.$page.props.shouldLogin = true;
        }
    },
};
</script>



<style scoped lang="scss">
@import "@css/variables";
@import "@css/mixins";

$width: 700px;
.w-main {
    padding-top: 10vh;
    .w-container {
        position: relative;
        width: $width;
        margin: 0 auto;
        background: white;
        padding: 20px;
        @include rwd(700px){
            width: 100%;
        };
    }

    .menus {
        margin: 20px auto;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;

        .menu {
            padding: 5px;
            text-align: center;
            background: $cGrayddd;
            border-radius: 40px;
            margin: 10px;
            min-width: 80px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;

            &:hover {
                background: $cOrange;
            }

            &.active {
                background: $cOrange;
            }
        }
    }

    .date-selector {
        display: flex;
        margin: 20px auto;
        width: calc(#{$width} / 2);
        @include rwd(450px) {
            width: 90%;
        }
    ;

        .date-display {
            flex-grow: 2;
            text-align: center;
        }

        i {
            cursor: pointer;

            &.disabled {
                color: #cbd5e0;
                cursor: not-allowed;
            }
        }
    }
}
</style>
