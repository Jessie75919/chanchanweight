<?php

namespace App\Services\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserInfoDTO
{
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var Carbon|null
     */
    private $emailVerifiedAt;

    /**
     * UserInfoDTO constructor.
     */
    public function __construct(string $email, string $password, ?string $name = null, ?Carbon $emailVerifiedAt = null)
    {
        $this->email = $email;
        $this->password = Hash::make($password);
        $this->name = $name;
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Carbon|null
     */
    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->emailVerifiedAt;
    }


}
