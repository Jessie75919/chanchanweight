<?php

namespace App\Services\AmountStore;

use App\Models\User;
use App\Models\WeightState;

class AmountStoreService
{
    private $date;
    private $weight;
    private $fat;
    private $temperature;
    private $user;

    /**
     * AmountStoreService constructor.
     * @param $date
     */
    public function __construct(User $user, $date)
    {
        $this->date = $date;
        $this->user = $user;
    }

    public function store()
    {
        $weightState = WeightState::firstOrCreate([
            'user_id' => $this->user->id,
            'date' => $this->date
        ]);

        if (isset($this->weight)) {
            $weightState->weight_amount = $this->weight;
        }

        if (isset($this->fat)) {
            $weightState->fat_ratio = $this->fat;
        }

        if (isset($this->temperature)) {
            $weightState->body_temperature = $this->temperature;
        }

        $weightState->save();
    }

    /**
     * @param  mixed  $weight
     * @return AmountStoreService
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    /**
     * @param  mixed  $fat
     * @return AmountStoreService
     */
    public function setFat($fat)
    {
        $this->fat = $fat;
        return $this;
    }

    /**
     * @param  mixed  $temperature
     * @return AmountStoreService
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;
        return $this;
    }

}
