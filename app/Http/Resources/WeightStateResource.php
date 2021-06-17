<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WeightStateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'date' => $this->date ? $this->date->toDateString() : "",
            'weight' => (float) $this->weight_amount,
            'temperature' => (float) $this->body_temperature,
            'fat' => (float) $this->fat_ratio,
        ];
    }
}
