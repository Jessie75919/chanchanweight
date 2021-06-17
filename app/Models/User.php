<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'birth',
        'email_verified_at',
    ];

    protected $dates = ['birth'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function weightStates(): HasMany
    {
        return $this->hasMany(WeightState::class);
    }

    public function bodyStatuses()
    {
        return $this->belongsToMany(BodyStatus::class, 'user_body_status')
            ->withPivot('date')
            ->withTimestamps();
    }

    public function workoutStatuses()
    {
        return $this->belongsToMany(WorkoutStatus::class, 'user_workout_status')
            ->withPivot('date')
            ->withTimestamps();
    }

    public function medicineStatuses()
    {
        return $this->belongsToMany(MedicineStatus::class, 'user_medicine_status')
            ->withPivot('date')
            ->withTimestamps();
    }

    public function sendPasswordResetNotification($token)
    {
        $url = url(route('user:reset-password-page', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset()
        ], false));

        $this->notify(new ResetPasswordNotification($this, $url));
    }
}
