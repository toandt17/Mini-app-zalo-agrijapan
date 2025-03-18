<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserQuizAttempt extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'question_id',
        'selected_option',
        'is_correct',
        'points_earned',
        'reward_id',
        'spin_tickets',
        'attempt_time'
    ];

    /**
     * Get the user who attempted the quiz
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the quiz question that was attempted
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }

    /**
     * Get the reward associated with this attempt
     */
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }
}
