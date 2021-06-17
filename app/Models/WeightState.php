<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeightState extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOfDate($query, Carbon $date)
    {
        return $query->where('date', $date);
    }
}
