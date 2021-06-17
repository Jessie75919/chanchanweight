<template>
    <div class="calendar-main">
        <section class="calendar-container">
            <v-calendar is-expanded
                        @update:to-page="onChangeMonth"
                        :max-date="new Date()"
                        :attributes="attributes">
                <template v-slot:day-popover="{day, attributes, dayTitle}">
                    <div class="w-popover-container">
                        <div class="popoverTitle">{{ dayTitle }}</div>
                        <ul v-for="{key, customData} in attributes" :key="key">
                            <li>
                                <div class="list-item">
                                    <span>體重：</span>
                                    <input type="number" v-if="customData.isOpen"
                                           v-model.number="customData.weight">
                                    <span v-else>{{ customData.weight }} kg</span>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <span>體脂：</span>
                                    <input type="number" v-if="customData.isOpen"
                                           v-model.number="customData.fat">
                                    <span v-else>{{ customData.fat }} %</span>
                                </div>
                            </li>
                            <li>
                                <div class="list-item">
                                    <span>體溫：</span>
                                    <input type="number" v-if="customData.isOpen"
                                           v-model.number="customData.temperature">
                                    <span v-else>{{ customData.temperature }} ℃</span>
                                </div>
                            </li>
                            <li class="buttons">
                                <div v-if="customData.isOpen"
                                     class="active"
                                     @click="updateAmount(customData, 'weight')">
                                    確認修改
                                </div>
                                <div v-else
                                     @click="enableInput(customData, 'weight')">
                                    數值修改
                                </div>
                            </li>
                            <li class="body-status-btn">
                                <div>
                                    <button @click="showBodyStatus(customData)">身體狀況</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </template>
            </v-calendar>
        </section>
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

export default {
    name: 'Calendar',
    layout: Layout,
    components: { BodyStatusInput },
    data() {
        return {
            weightStates: [],
            selectedDate: null,
            isOpenBodyStatus: false,
        };
    },
    methods: {
        closeBodyStatusInput() {
            this.isOpenBodyStatus = false;
            this.selectedDate = null;
        },
        onChangeMonth(e) {
            const { year, month } = e;
            this.getWeightStatesByMonth(year, month);
        },
        getWeightStatesByMonth(year, month) {
            if (!this.isLogin) {
                return;
            }
            return weightStatesByMonth({ year, month })
                .then(({ data }) => this.weightStates = data);
        },
        updateAmount(ws) {
            ws.isOpen = false;
            const { date } = ws;
            submit(ws, date);
        },
        enableInput(ws) {
            ws.isOpen = true;
        },
        showBodyStatus(ws) {
            const { date } = ws;
            this.selectedDate = { date, display: date };
            this.isOpenBodyStatus = true;
        },
    },
    computed: {
        isLogin() {
            return this.$page.props.auth.user;
        },
        attributes() {
            return [
                ...this.weightStates.map(ws => {
                    ws.isOpen = false;
                    return {
                        dates: ws.date,
                        dot: { style: { backgroundColor: '#E3964B' } },
                        popover: true,
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
.calendar-main {
    padding-top: 10vh;
    display: flex;
    justify-content: center;
    align-items: center;

    .calendar-container {
        width: 600px;
    }
}
</style>
<style lang="scss">
@import "@css/variables";

.vc-container {
    border: none;
    background: none;

    .vc-header {
        padding-bottom: 20px;
    }

    .vc-day {
        min-height: 70px;
    }
}

.w-popover-container {
    min-width: 100px;

    .popoverTitle {
        text-align: center;
    }

    li {
        list-style: none;
        padding: 0;

        .list-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        &:before {
            content: "";
            position: absolute;
            width: 5px;
            height: 5px;
            background: $cOrange;
            left: -13px;
            top: 11px;
            border-radius: 50%;
        }

        &.buttons {
            text-align: center;

            div {
                width: 100%;
                margin: 5px auto;
                border-radius: 5px;
                background: $cOrange;
                text-align: center;
                cursor: pointer;

                i {
                    font-weight: bold;
                    color: white;
                    font-size: 14px;
                }

                &.active {
                    background: $cGreen;
                }
            }

            &:before {
                background: none;
            }
        }

        &.body-status-btn {
            button {
                width: 100%;
                background: $cBlue;
                color: white;
                border: none;
                padding: 5px;
                border-radius: 5px;
            }

            &:before {
                background: none;
            }
        }

        input {
            width: 50px;
            border-radius: 15px;
            height: 20px;
            text-align: right;
        }
    }
}


</style>
