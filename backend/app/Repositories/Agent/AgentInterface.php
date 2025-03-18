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

    /**
     * Tạo mã QR cho đại lý
     */
    public function generateQrCode($id);

    /**
     * Tạo mã barcode cho đại lý
     *
     * @param int $agentId ID của đại lý
     * @param string|null $orderCode Mã đơn hàng (nếu có)
     * @return bool
     */
    public function generateBarcode($agentId, $orderCode = null);
}
