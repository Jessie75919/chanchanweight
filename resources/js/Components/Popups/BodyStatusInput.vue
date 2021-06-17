<template>
    <Modal width="mid">
        <template v-slot:header>
            <h3>{{date.display}}</h3>
        </template>
        <template v-slot:body>
            <div class="stacks">
                <div class="title">
                    <i class="icon-sad"></i>
                    身體狀況
                </div>
                <div class="status-container">
                    <div class="status"
                         :class="{selected: selected.body[status.id]}"
                         @click="selectToggle(status.id, 'body')"
                         v-for="status in statusCategory.body"
                         :key="'body-' + status.id">
                        {{ status.name }}
                    </div>
                </div>

            </div>
            <div class="stacks">
                <div class="title">
                    <i class="icon-pingpong"></i>
                    運動狀態
                </div>
                <div class="status-container">
                    <div class="status" v-for="status in statusCategory.workout"
                         :key="'workout-' + status.id"
                         :class="{selected: selected.workout[status.id]}"
                         @click="selectToggle(status.id, 'workout')"
                    >
                        {{ status.name }}
                    </div>
                </div>
            </div>
            <div class="stacks">
                <div class="title">
                    <i class="icon-pill"></i>
                    用藥狀況
                </div>
                <div class="status-container">
                    <div class="status" v-for="status in statusCategory.medicine"
                         :key="'medicine-' + status.id"
                         :class="{selected: selected.medicine[status.id]}"
                         @click="selectToggle(status.id, 'medicine')"
                    >
                        {{ status.name }}
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:footer>
            <a href="#" @click.prevent="$emit('close')">關閉</a>
            <input type="button"
                   value="紀錄"
                   class="send"
                   @click="save"
            >
        </template>
    </Modal>
</template>

<script>
import { getStateCategories, getUserSelectedStatusByDate } from '@/APIs/BodyStatus';
import Modal from '@/Components/Base/Modal';
import Toast from '@/ThirdParty/Toast/Toast';
import { Inertia } from '@inertiajs/inertia';
import { reactive } from 'vue';

const submit = (data, date, ctx) => {
    const { body, workout, medicine } = data;
    const payload = {
        body: Object.keys(body),
        workout: Object.keys(workout),
        medicine: Object.keys(medicine),
        date,
    };
    Inertia.post('/weight/store/statuses', payload, {
            onSuccess: () => {
                ctx.emit('close');
                Toast.success('記錄存檔成功囉！');
            },
        },
    );
};
export default {
    name: 'BodyStatusInput',
    components: { Modal },
    props: { date: Object },
    setup: function(props, context) {
        const { date } = props;
        const statusCategory = reactive({ body: null, workout: null, medicine: null });
        getStateCategories()
            .then(data => {
                const { body_statuses, medicine_statuses, workout_statuses } = data;
                statusCategory.body = body_statuses;
                statusCategory.workout = workout_statuses;
                statusCategory.medicine = medicine_statuses;
            });
        const selected = reactive({ body: {}, workout: {}, medicine: {} });
        getUserSelectedStatusByDate(date.date)
            .then(data => {
                const arrToObjKeys = (arr) => arr.reduce((acc, curr) => (acc[curr] = 1, acc), {});
                selected.body = arrToObjKeys(data.body_statuses);
                selected.workout = arrToObjKeys(data.workout_statuses);
                selected.medicine = arrToObjKeys(data.medicine_statuses);
            });

        const save = () => submit(selected, date.date, context);

        // in order to check the id existence as soon as possible,
        // put the id as key of object to get the value to validate
        // therefore it can skip the search process by the array case
        const selectToggle = (selectedItemId, category) =>
            selected[category][selectedItemId]
                ? delete selected[category][selectedItemId]
                : selected[category][selectedItemId] = true;

        return { date, statusCategory, save, selected, selectToggle };
    },
};
</script>
<style scoped lang="scss">
@import "@css/variables";
@import "@css/mixins";

h2 {
    margin-bottom: 1rem;
}

.stacks {
    display: flex;
    padding: 10px 0;
    margin-top: 10px;
    border-bottom: 1px dashed $cBlue;
    @include rwd(600px) {
        flex-direction: column;
    }

    .title {
        width: 22%;
        margin-bottom: 10px;
        @include rwd(600px) {
            width: 100%
        }
        font-weight: bold;

        i {
            color: $cOrange;
            position: relative;
            top: 3px;
        }
    }

    .status-container {
        width: 78%;
        @include rwd(600px) {
            width: 100%
        }
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;

        .status {
            color: $cGray666;
            text-align: center;
            padding: 2px;
            margin: 0 2px 10px;
            width: 23%;
            height: 30px;
            border: 1px solid $cGray999;
            border-radius: 45px;
            cursor: pointer;

            @include rwd(400px){
                font-size: 14px;
            };

            &.selected {
                border: 1px solid $cBlue;
                background: $cBlue;
                color: white;
                font-weight: bold;
            }
        }
    }
}
</style>
