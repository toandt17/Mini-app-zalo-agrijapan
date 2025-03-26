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
        'qr_code',
        'qr_regeneration_count',
        'qr_code_generated_at',
        'code_agent',
    ];

    /**
     * Các trường được tự động chuyển thành đối tượng Carbon
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'qr_code_generated_at',
    ];

    /**
     * Các thuộc tính cần được cast
     */
    protected $casts = [
        'qr_code_generated_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
        'qr_regeneration_count' => 'integer',
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

    /**
     * Lấy tất cả mã QR của đại lý
     */
    public function qrCodes()
    {
        return $this->hasMany(AgentQrCode::class);
    }

    /**
     * Lấy mã QR mới nhất của đại lý
     */
    public function latestQrCode()
    {
        return $this->qrCodes()->latest('generated_at')->first();
    }

    /**
     * Lấy tất cả mã barcode của đại lý
     */
    public function barcodes()
    {
        return $this->hasMany(AgentBarcode::class);
    }

    /**
     * Lấy mã barcode mới nhất của đại lý
     */
    public function latestBarcode()
    {
        return $this->hasOne(AgentBarcode::class)
            ->where('status', 'active')
            ->latest('generated_at');
    }

    /**
     * Lấy mã barcode mới nhất của đại lý (kết quả truy vấn)
     */
    public function getLatestBarcodeAttribute()
    {
        return $this->barcodes()
            ->where('status', 'active')
            ->latest('generated_at')
            ->first();
    }

    /**
     * Tạo mã quy ước đại lý nếu chưa có
     */
    public function generateCodeAgentIfNotExists()
    {
        if (empty($this->code_agent)) {
            // Format: AG + ID của đại lý (đủ 5 chữ số, thêm 0 ở đầu nếu cần)
            $this->code_agent = 'AG' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
            $this->save();
        }

        return $this->code_agent;
    }

    /**
     * Lấy mã QR mới nhất của đại lý kèm thông tin số lần quét
     */
    public function getLatestQrCodeWithScanInfoAttribute()
    {
        return $this->qrCodes()
            ->latest('generated_at')
            ->first();
    }
}

