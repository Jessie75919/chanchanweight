<?php

use Illuminate\Database\Migrations\Migration;

class AddStatusDefaultData extends Migration
{
    public function up()
    {
        $bodyStatus = ['頭痛', '發燒', '咳嗽', '便祕', '腹瀉', '胃痛', '生理期', '食慾不振', '食慾旺盛', '失眠', '水腫', '過敏',];
        $workoutStatus = ['無', '有氧運動', '重訓', '瑜伽', '快走',];
        $medicineStatus = ['感冒藥', '止痛藥', '慢性楚方', '特殊藥物'];

        DB::table('body_statuses')->insert($this->mapTo($bodyStatus));
        DB::table('workout_statuses')->insert($this->mapTo($workoutStatus));
        DB::table('medicine_statuses')->insert($this->mapTo($medicineStatus));
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        DB::table('body_statuses')->truncate();
        DB::table('workout_statuses')->truncate();
        DB::table('medicine_statuses')->truncate();
    }

    private function mapTo($items)
    {
        return collect($items)->map(function ($item) {
            return [
                'name' => $item,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();
    }
}
