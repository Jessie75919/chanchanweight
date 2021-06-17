<?php

namespace Database\Seeders;

use App\Models\BodyStatus;
use App\Models\MedicineStatus;
use App\Models\User;
use App\Models\WeightState;
use App\Models\WorkoutStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->generateData();
    }

    private function generateData(): void
    {
        $now = now();
        /* @var User $user */
        $user = User::find(1);
        $bodyStatusIds = BodyStatus::all()->pluck('id');
        $workoutStatusIds = WorkoutStatus::all()->pluck('id');
        $medicineStatusIds = MedicineStatus::all()->pluck('id');
        for ($i = 2 ; $i >= 0 ; $i--) {
            $preMonth = $now->copy()->subMonths($i);
            foreach (range(1, 30) as $day) {
                $date = "{$preMonth->year}-{$preMonth->month}-{$day}";
                if ($now->lessThan(Carbon::parse($date))) {
                    break;
                }
                $data = ['date' => $date];
                WeightState::factory()->create($data);
                $user->bodyStatuses()->attach([$bodyStatusIds->random() => $data]);
                $user->workoutStatuses()->attach([$workoutStatusIds->random() => $data]);
                $user->medicineStatuses()->attach([$medicineStatusIds->random() => $data]);
            }
        }
    }
}
