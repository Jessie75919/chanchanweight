<?php

namespace App\Services\BodyStatus;

use App\Models\User;

class BodyStatusService
{
    public function store(BodyStatusStoreDTO $dto)
    {
        $user = $dto->user();
        $date = $dto->date();
        $this->updateStatusesInDate($user->bodyStatuses(), $dto->bodyStatusIds(), $date);
        $this->updateStatusesInDate($user->workoutStatuses(), $dto->workoutStatusIds(), $date);
        $this->updateStatusesInDate($user->medicineStatuses(), $dto->medicineStatusIds(), $date);
    }

    public function getStatuses(User $user, string $date)
    {
        return collect(['bodyStatuses', 'workoutStatuses', 'medicineStatuses'])
            ->map(function ($relation) use ($user, $date) {
                return $user->$relation()->wherePivot('date', $date)->get()->pluck('id');
            });
    }

    public function updateStatusesInDate($builder, array $statusIds, string $date)
    {
        /*  detach old statuses */
        $builder->wherePivot('date', $date)->detach();
        /* add new statuses */
        $statusIdsWithDate =
            collect($statusIds)->mapWithKeys(function (int $id) use ($date) {
                return [$id => ['date' => $date]];
            });

        $builder->attach($statusIdsWithDate);
    }
}
