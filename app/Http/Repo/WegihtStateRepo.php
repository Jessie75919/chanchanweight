<?php

namespace App\Http\Repo;

use App\Models\User;
use Carbon\Carbon;

class WegihtStateRepo
{
    public function getByDate(User $user, string $date)
    {
        return $user->weightStates()->ofDate(Carbon::parse($date))->first();
    }

    public function getByMonth(User $user, int $year, int $month)
    {
        $begin = Carbon::create($year, $month, 1);
        $end = $begin->copy()->endOfMonth();
        return $user->weightStates()
            ->whereDate('date', '>=', $begin)
            ->whereDate('date', '<=', $end)
            ->get();
    }
}
