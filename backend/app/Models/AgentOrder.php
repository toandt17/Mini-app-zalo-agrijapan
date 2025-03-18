<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'order_code',
        'barcode',
        'barcode_path',
        'metadata',
        'status',
        'used_at',
        'generated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'metadata' => 'array',
        'used_at' => 'datetime',
        'generated_at' => 'datetime',
    ];

    /**
     * Get the agent that owns this order
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
