<template>
    <div class="calendar-main">
        <section class="calendar-container">
            <v-calendar is-expanded
                        @update:to-page="onChangeMonth"
                        @dayclick="onDayClick"
                        :max-date="new Date()"
                        :attributes="attributes">
            </v-calendar>
        </section>
        <div class="data-container" v-if="editingData">
            <div class="data-header">
                {{ selectedDate.display }}
                <i class="icon-cancel" @click="resetData"></i>
            </div>
            <div class="data-body">
                <div class="metrics">
                    <div class="metric-values">
                        <div class="title">體重</div>
                        <div class="value" v-if="! isEditData">{{ editingData.weight }} kg</div>
                        <input type="number" v-else v-model="editingData.weight">
                    </div>
                    <div class="metric-values">
                        <div class="title">體脂</div>
                        <div class="value" v-if="! isEditData">{{ editingData.fat }} %</div>
                        <input type="number" v-else v-model="editingData.fat">
                    </div>
                    <div class="metric-values">
                        <div class="title">體溫</div>
                        <div class="value" v-if="! isEditData">{{ editingData.temperature }} ℃</div>
                        <input type="number" v-else v-model="editingData.temperature">
                    </div>
                </div>
                <div class="buttons">
                    <button class="edit" @click="editData" v-if="! isEditData">修改數值</button>
                    <button class="confirm" @click="confirmEdit" v-else>確定修改</button>
                    <button class="body-status" @click="showBodyStatus">身體狀態</button>
                </div>
            </div>
        </div>
    </div>
    <transition name="fade">
        <BodyStatusInput v-if="isOpenBodyStatus" :date="selectedDate" @close="closeBodyStatusInput"/>
    </transition>
</template>

<script>
import { weightStatesByMonth } from '@/APIs/WeightState';
import BodyStatusInput from '@/Components/Popups/BodyStatusInput';
import { submit } from '@/Components/Popups/shares/shares';
import Layout from '@/Layouts/Layout';
import { inject } from 'vue';

export default {
    name: 'Calendar',
    layout: Layout,
    components: { BodyStatusInput },
    setup(){
        const dayjs = inject('dayjs');
        return {dayjs};
    },
    data() {
        return {
            weightStates: [],
            selectedDate: null,
            isOpenBodyStatus: false,
            isEditData: false,
            editingData: null,
        };
    },
    methods: {
        resetData(){
            this.editingData = null;
            this.selectedDate = null;
        },
        onDayClick(e) {
            if (!e.attributes[0]) {
                this.resetData();
                return;
            }
            this.editingData = e.attributes[0].customData;
            const { date } = this.editingData;
            this.selectedDate = { date, display: this.dayjs(date).format('YYYY 年 MM 月 DD 日') };
        },
        editData() {
            if (!this.editingData) {
                return;
            }
            this.isEditData = true;
        },
        confirmEdit() {
            this.isEditData = false;
            submit(this.editingData, this.selectedDate.date);
        },
        closeBodyStatusInput() {
            this.isOpenBodyStatus = false;
            this.resetData();
        },
        onChangeMonth(e) {
            const { year, month } = e;
            this.resetData();
            this.getWeightStatesByMonth(year, month);
        },
        getWeightStatesByMonth(year, month) {
            if (!this.isLogin) {
                return;
            }
            return weightStatesByMonth({ year, month })
                .then(({ data }) => this.weightStates = data);
        },
        showBodyStatus() {
            this.isOpenBodyStatus = true;
        },
    },
    computed: {
        isLogin() {
            return this.$page.props.auth.user;
        },
        attributes() {
            return [
                {
                    key: 'today',
                    highlight: {
                        color: 'red',
                    },
                    dates: new Date(),
                },
                ...this.weightStates.map(ws => {
                    ws.isSelected = false;
                    return {
                        dates: ws.date,
                        highlight: ws.isSelected,
                        dot: { style: { backgroundColor: '#E3964B' } },
                        customData: ws,
                    };
                }),
            ];
        },
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

.calendar-main {
    padding-top: 10vh;
    @include rwd(600px) {
        padding-top: 5vh;
    }
    @include rwd(450px) {
        padding-top: 2vh;
    }

    .calendar-container, .data-container {
        margin: 0 auto;
        max-width: 600px;
        width: 100%;
    }

    .data-container {
        margin-top: 20px;
        background: white;
        border-radius: 10px;
        padding: 20px;

        .data-header {
            text-align: center;
            margin-bottom: 10px;
            position: relative;
            i {
                position: absolute;
                cursor: pointer;
                top: -5px;
                right: -5px;
                font-size: 13px;
            }
        }

        .data-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            @include rwd(600px) {
                flex-direction: column;
            }
        }


        .metrics {
            width: 60%;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 5px;

            @include rwd(600px) {
                width: 90%;
                margin-bottom: 10px;
            }

            @include rwd(450px) {
                flex-direction: column;
            }

            .metric-values {
                @include rwd(450px) {
                    display: flex;
                    width: 100%;
                    justify-content: space-between;
                    margin-bottom: 10px;
                }

                input {
                    width: 75%;
                    height: 25px;
                    color: #999;
                }

                .title {
                    font-size: 18px;
                }

                .value {
                    font-size: 16px;
                    color: #999;
                }
            }
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 40%;
            @include rwd(600px) {
                width: 100%;
            }

            button {
                width: 50%;
                margin: 0 5px;
                border: none;
                padding: 5px;
                border-radius: 50px;
                color: #fff;
                cursor: pointer;
            }

            .edit {
                background: $cOrange;
            }

            .confirm {
                background: $cGreen;
            }

            .body-status {
                background: $cBlue;
            }
        }
    }
}
</style>
<style lang="scss">

.vc-container {
    border: none;
    background: none;

    .vc-header {
        padding-bottom: 20px;
    }

    .vc-day {
        min-height: 40px;
    }
}

</style>
