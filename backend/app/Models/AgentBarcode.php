<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentBarcode extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt
     */
    protected $fillable = [
        'agent_id',
        'barcode_value',
        'barcode_image',
        'agent_code',
        'order_code',
        'status',
        'generated_at',
        'metadata',
    ];

    /**
     * Các trường được tự động chuyển thành đối tượng Carbon
     */
    protected $dates = [
        'generated_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Các thuộc tính cần được cast
     */
    protected $casts = [
        'generated_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Thuộc tính được thêm vào kết quả JSON
     */
    protected $appends = ['code_agent'];

    /**
     * Lấy thông tin đại lý sở hữu mã barcode này
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Lấy mã quy ước đại lý từ metadata hoặc từ agent_code
     *
     * @return string|null
     */
    public function getCodeAgentAttribute()
    {
        if (isset($this->metadata['code_agent'])) {
            return $this->metadata['code_agent'];
        }

        return $this->agent_code;
    }
}
