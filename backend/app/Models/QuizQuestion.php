<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
        'difficulty_level',
        'points_reward',
        'reward_id',
        'spin_tickets'
    ];

    /**
     * Get the reward associated with this question
     */
    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }

    /**
     * Get the user quiz attempts for this question
     */
    public function userAttempts(): HasMany
    {
        return $this->hasMany(UserQuizAttempt::class, 'question_id');
    }
}
