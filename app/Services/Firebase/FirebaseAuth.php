<?php

namespace App\Services\Firebase;

use Kreait\Firebase\Auth\UserRecord;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FirebaseAuth
{
    private $auth;

    public function __construct()
    {
        $this->auth = Firebase::auth();
    }

    public function getUser(string $uid): ?UserRecord
    {
        try {
            return $this->auth->getUser($uid);
        } catch (AuthException | FirebaseException $e) {
            \Log::info('user not found with uid => '.$uid);
        }
        return null;
    }
}
