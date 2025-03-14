<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'province_id',
        'district_id',
        'ward_id',
        'latitude',
        'longitude',
        'status',
        'image',
        'description',
        'open_hours',
    ];

    /**
     * Thêm các thuộc tính được append vào JSON
     */
    protected $appends = ['full_address'];

    /**
     * Lấy tỉnh/thành phố của đại lý
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    /**
     * Lấy quận/huyện của đại lý
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Lấy phường/xã của đại lý
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Lấy địa chỉ đầy đủ của đại lý
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [];

        if ($this->address) {
            $parts[] = $this->address;
        }

        if ($this->ward && $this->ward->name) {
            $parts[] = $this->ward->name;
        }

        if ($this->district && $this->district->name) {
            $parts[] = $this->district->name;
        }

        if ($this->province && $this->province->name) {
            $parts[] = $this->province->name;
        }

        return implode(', ', $parts);
    }
}
