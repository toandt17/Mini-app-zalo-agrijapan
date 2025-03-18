<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
       'name',
       'email',
       'password',
       'zalo_id',
       'avatar',
       'phone',
       'id_by_oa',
      'followed_oa',
      'is_sensitive',
      'points',
      'last_login'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the point transactions for the user
     */
    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * Get the daily check-ins for the user
     */
    public function dailyCheckins(): HasMany
    {
        return $this->hasMany(DailyCheckin::class);
    }

    /**
     * Get the completed missions for the user
     */
    public function userMissions(): HasMany
    {
        return $this->hasMany(UserMission::class);
    }

    /**
     * Get all missions completed by the user
     */
    public function missions()
    {
        return $this->belongsToMany(Mission::class, 'user_missions')
                    ->withPivot('completed_at', 'spin_tickets_earned');
    }

    /**
     * Get the quiz attempts for the user
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(UserQuizAttempt::class);
    }

    /**
     * Get the spins for the user
     */
    public function spins(): HasMany
    {
        return $this->hasMany(UserSpin::class);
    }
}
