<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'reward_id',
        'points_reward',
        'spin_tickets',
        'action_required',
        'reward_spin_tickets'
    ];

    /**
     * Get the reward associated with this mission
     */
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }

    /**
     * Get the user missions associated with this mission
     */
    public function userMissions(): HasMany
    {
        return $this->hasMany(UserMission::class);
    }

    /**
     * Get the users who completed this mission
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_missions')
                    ->withPivot('completed_at', 'spin_tickets_earned');
    }
}
