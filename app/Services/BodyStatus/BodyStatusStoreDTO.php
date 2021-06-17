<?php

namespace App\Services\BodyStatus;

use App\Models\User;

class BodyStatusStoreDTO
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $date;
    /**
     * @var array
     */
    private $bodyStatusIds;
    /**
     * @var array
     */
    private $workoutStatusIds;
    /**
     * @var array
     */
    private $medicineStatusIds;

    /**
     * BodyStatusStoreDTO constructor.
     */
    public function __construct(
        User $user,
        string $date,
        array $bodyStatusIds,
        array $workoutStatusIds,
        array $medicineStatusIds
    )
    {
        $this->user = $user;
        $this->date = $date;
        $this->bodyStatusIds = $bodyStatusIds;
        $this->workoutStatusIds = $workoutStatusIds;
        $this->medicineStatusIds = $medicineStatusIds;
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function date(): string
    {
        return $this->date;
    }

    /**
     * @return array
     */
    public function bodyStatusIds(): array
    {
        return $this->bodyStatusIds;
    }

    /**
     * @return array
     */
    public function workoutStatusIds(): array
    {
        return $this->workoutStatusIds;
    }

    /**
     * @return array
     */
    public function medicineStatusIds(): array
    {
        return $this->medicineStatusIds;
    }
}
