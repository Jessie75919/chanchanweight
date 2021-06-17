<?php

namespace App\Http\Controllers\Weight;

use App\Http\Controllers\Controller;
use App\Http\Repo\WegihtStateRepo;
use App\Http\Requests\AmountStoreRequest;
use App\Http\Resources\WeightStateResource;
use App\Models\WeightState;
use App\Providers\RouteServiceProvider;
use App\Services\AmountStore\AmountStoreService;
use Illuminate\Http\Request;

class UserAmountsController extends Controller
{
    public function store(AmountStoreRequest $request)
    {
        $storer = new AmountStoreService($request->user(), $request->date);
        $storer->setWeight($request->weight)
            ->setFat($request->fat)
            ->setTemperature($request->temperature)
            ->store();

        return redirect()->back();
    }

    public function weightStatesData(Request $request, WegihtStateRepo $repo)
    {
        return new WeightStateResource(
            $repo->getByDate($request->user(), $request->date) ?? new WeightState
        );
    }

    public function weightStatesDataByMonth(Request $request, WegihtStateRepo $repo)
    {
        return WeightStateResource::collection(
            $repo->getByMonth($request->user(), $request->year, $request->month)
        );
    }
}
