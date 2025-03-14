<?php

namespace App\Repositories\Agent;

use App\Models\Agent;

class AgentRepository implements AgentInterface
{
    protected $agent;

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    /**
     * Lấy tất cả đại lý
     */
    public function getAllAgents()
    {
        return $this->agent->with(['province', 'district', 'ward'])->orderBy('created_at', 'desc')->get();
    }

    /**
     * Tìm đại lý theo ID
     */
    public function findById($id)
    {
        return $this->agent->with(['province', 'district', 'ward'])->find($id);
    }

    /**
     * Tạo mới đại lý
     */
    public function create(array $data)
    {
        return $this->agent->create($data);
    }

    /**
     * Cập nhật thông tin đại lý
     */
    public function update($id, array $data)
    {
        $agent = $this->agent->find($id);
        if ($agent) {
            $agent->update($data);
            return $agent;
        }
        return false;
    }

    /**
     * Xóa đại lý
     */
    public function delete($id)
    {
        $agent = $this->agent->find($id);
        if ($agent) {
            return $agent->delete();
        }
        return false;
    }

    /**
     * Lấy các đại lý theo vị trí
     */
    public function getAgentsByLocation($provinceId, $districtId = null, $wardId = null)
    {
        $query = $this->agent->where('province_id', $provinceId);

        if ($districtId) {
            $query->where('district_id', $districtId);
        }

        if ($wardId) {
            $query->where('ward_id', $wardId);
        }

        return $query->with(['province', 'district', 'ward'])->get();
    }
}
