<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpinWheel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'spin_wheel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prize_name',
        'description',
        'image',
        'probability',
        'remaining_quantity',
        'has_reward'
    ];

    /**
     * Get the user spins associated with this prize
     */
    public function userSpins(): HasMany
    {
        return $this->hasMany(UserSpin::class, 'prize_id');
    }
}
