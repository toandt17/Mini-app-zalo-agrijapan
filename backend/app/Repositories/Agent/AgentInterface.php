<?php

namespace App\Repositories\Agent;

interface AgentInterface
{
    /**
     * Lấy tất cả đại lý
     */
    public function getAllAgents();

    /**
     * Tìm đại lý theo ID
     */
    public function findById($id);

    /**
     * Tạo mới đại lý
     */
    public function create(array $data);

    /**
     * Cập nhật thông tin đại lý
     */
    public function update($id, array $data);

    /**
     * Xóa đại lý
     */
    public function delete($id);

    /**
     * Lấy các đại lý theo vị trí
     */
    public function getAgentsByLocation($provinceId, $districtId = null, $wardId = null);
}
