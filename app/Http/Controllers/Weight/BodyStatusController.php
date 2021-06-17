<?php

namespace App\Http\Controllers\Weight;

use App\Http\Controllers\API\ApiController;
use App\Http\Requests\BodyStatusRequest;
use App\Models\BodyStatus;
use App\Models\MedicineStatus;
use App\Models\WorkoutStatus;
use App\Providers\RouteServiceProvider;
use App\Services\BodyStatus\BodyStatusService;
use App\Services\BodyStatus\BodyStatusStoreDTO;
use DB;
use Illuminate\Http\Request;

class BodyStatusController extends ApiController
{
    public function statusCategories()
    {
        $bodyStatus = BodyStatus::select(['id', 'name'])->get();
        $workoutStatus = WorkoutStatus::select(['id', 'name'])->get();
        $medicineStatus = MedicineStatus::select(['id', 'name'])->get();

        return $this->respondWithArray([
            'body_statuses' => $bodyStatus,
            'workout_statuses' => $workoutStatus,
            'medicine_statuses' => $medicineStatus,
        ]);
    }

    public function store(BodyStatusRequest $request, BodyStatusService $service)
    {
        DB::transaction(function() use ($service, $request) {
            $service->store(
                new BodyStatusStoreDTO(
                    $request->user(),
                    $request->date,
                    $request->body,
                    $request->workout,
                    $request->medicine
                )
            );
        });

        return redirect()->back();
    }

    public function selected(Request $request, BodyStatusService $service)
    {
        [$bodyStatus, $workoutStatus, $medicineStatus] =
            $service->getStatuses($request->user(), $request->date);

        return $this->respondWithArray([
            'body_statuses' => $bodyStatus,
            'workout_statuses' => $workoutStatus,
            'medicine_statuses' => $medicineStatus,
        ]);
    }
}
