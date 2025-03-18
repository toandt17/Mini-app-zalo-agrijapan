<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reward extends Model
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
        'image',
        'quantity'
    ];

    /**
     * Get the missions associated with this reward
     */
    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class);
    }

    /**
     * Get the quiz questions associated with this reward
     */
    public function quizQuestions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Get the quiz attempts associated with this reward
     */
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(UserQuizAttempt::class);
    }
}
