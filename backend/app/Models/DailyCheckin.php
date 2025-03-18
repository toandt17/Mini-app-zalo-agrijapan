<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCheckin extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'daily_checkin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'checkin_date',
        'spin_tickets',
        'points_earned'
    ];

    /**
     * Get the user that owns the check-in
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
