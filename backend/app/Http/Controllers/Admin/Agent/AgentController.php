<?php

namespace App\Http\Controllers\Admin\Agent;

use App\Http\Controllers\Controller;
use App\Repositories\Agent\AgentInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AgentController extends Controller
{
    protected $agentRepository;

    public function __construct(AgentInterface $agentRepository)
    {
        $this->agentRepository = $agentRepository;
    }

    public function index()
    {
        $agents = $this->agentRepository->getAllAgents();
        return view('admin.agents.index', compact('agents'));
    }

    public function add()
    {
        $provinces = \App\Models\Province::orderBy('name')->get();
        return view('admin.agents.add', compact('provinces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id' => 'required|exists:wards,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'open_hours' => 'nullable|string|max:255',
            'code_agent' => 'nullable|string|max:20|unique:agents,code_agent',
        ]);

        $data = $request->all();

        // Xử lý hình ảnh nếu có
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/agents', $imageName);
            $data['image'] = 'agents/' . $imageName;
        }

        $agent = $this->agentRepository->create($data);

        // Đảm bảo đại lý có mã quy ước
        if (empty($agent->code_agent)) {
            $agent->generateCodeAgentIfNotExists();
        }

        return redirect()->route('admin.agents.index')
            ->with('success', 'Đại lý đã được thêm thành công.');
    }

    /**
     * Hiển thị thông tin chi tiết của đại lý
     */
    public function show($id)
    {
        $agent = $this->agentRepository->findById($id);

        if (!$agent) {
            return redirect()->route('admin.agents.index')
                ->with('error', 'Không tìm thấy đại lý.');
        }

        // Lấy mã QR mới nhất của đại lý kèm thông tin quét
        $latestQrCode = $agent->getLatestQrCodeWithScanInfoAttribute();

        return view('admin.agents.show', compact('agent', 'latestQrCode'));
    }

    public function edit($id)
    {
        $agent = $this->agentRepository->findById($id);
        if (!$agent) {
            return redirect()->route('admin.agents.index')
                ->with('error', 'Không tìm thấy đại lý.');
        }

        $provinces = \App\Models\Province::orderBy('name')->get();
        return view('admin.agents.edit', compact('agent', 'provinces'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'ward_id' => 'required|exists:wards,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'open_hours' => 'nullable|string|max:255',
            'code_agent' => 'nullable|string|max:20|unique:agents,code_agent,' . $id,
        ]);

        $data = $request->all();

        // Xử lý hình ảnh nếu có
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/agents', $imageName);
            $data['image'] = 'agents/' . $imageName;
        }

        $agent = $this->agentRepository->update($id, $data);

        // Đảm bảo đại lý có mã quy ước
        if ($agent && empty($agent->code_agent)) {
            $agent->generateCodeAgentIfNotExists();
        }

        return redirect()->route('admin.agents.index')
            ->with('success', 'Đại lý đã được cập nhật thành công.');
    }

    public function delete($id)
    {
        $this->agentRepository->delete($id);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Đại lý đã được xóa thành công.');
    }

    /**
     * Tạo lại mã QR cho đại lý
     */
    public function regenerateQrCode($id)
    {
        try {
            $success = $this->agentRepository->generateQrCode($id);

            if ($success) {
                // Lấy lại thông tin đại lý mới nhất sau khi tạo mã QR
                $agent = $this->agentRepository->findById($id);

                if ($agent && $agent->qr_code_generated_at) {
                    $formattedTime = $agent->qr_code_generated_at->format('d/m/Y H:i:s');
                    Session::flash('success', 'Đã tạo mã QR mới thành công lúc ' . $formattedTime . '.
                        Mã QR này sẽ chuyển hướng tới trang agrijapanvn.com.vn/agents/' . $agent->id . '
                        và chứa thông tin thời gian tạo (' . $formattedTime . ') để hiển thị cho người dùng khi quét.');
                } else {
                    Session::flash('success', 'Mã QR mới đã được tạo thành công.');
                }
            } else {
                Session::flash('error', 'Không thể tạo lại mã QR. Vui lòng kiểm tra log để biết thêm chi tiết.');
            }

            // Thêm timestamp vào URL để đảm bảo không bị cache
            $timestamp = time();

            // Chuyển hướng về trang trước
            if (url()->previous() == url()->current()) {
                return redirect()->route('admin.agents.index', ['refresh' => $timestamp]);
            }

            return redirect()->back()->with('refresh', $timestamp);
        } catch (\Exception $e) {
            Session::flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Hiển thị trang in mã QR cho nhiều đại lý
     */
    public function printQrCodes(Request $request)
    {
        $provinceId = $request->get('province_id');
        $districtId = $request->get('district_id');

        // Lấy danh sách tỉnh/thành phố
        $provinces = \App\Models\Province::orderBy('name')->get();

        // Lọc đại lý theo province_id và district_id (nếu có)
        if ($provinceId) {
            if ($districtId) {
                $agents = $this->agentRepository->getAgentsByLocation($provinceId, $districtId);
            } else {
                $agents = $this->agentRepository->getAgentsByLocation($provinceId);
            }
        } else {
            $agents = $this->agentRepository->getAllAgents();
        }

        // Chỉ lấy các đại lý có mã QR
        $agentsWithQR = $agents->filter(function($agent) {
            return !empty($agent->qr_code);
        });

        return view('admin.agents.print-qr-codes', compact('agentsWithQR', 'provinces', 'provinceId', 'districtId'));
    }

    /**
     * In một mã QR duy nhất
     */
    public function printSingleQrCode($id)
    {
        $agent = $this->agentRepository->findById($id);

        if (!$agent || empty($agent->qr_code)) {
            return redirect()->route('admin.agents.index')
                ->with('error', 'Không tìm thấy đại lý hoặc đại lý chưa có mã QR.');
        }

        return view('admin.agents.print-single-qr', compact('agent'));
    }

    /**
     * Hiển thị lịch sử mã QR của đại lý
     */
    public function qrCodeHistory($id)
    {
        $agent = $this->agentRepository->findById($id);

        if (!$agent) {
            return redirect()->route('admin.agents.index')
                ->with('error', 'Không tìm thấy đại lý.');
        }

        // Lấy lịch sử mã QR và đảm bảo các trường datetime được xử lý đúng
        $qrCodes = \App\Models\AgentQrCode::where('agent_id', $id)
            ->orderBy('generated_at', 'desc')
            ->get()
            ->map(function($qr) {
                // Chuyển đổi các trường datetime thành Carbon object nếu chúng là string
                if ($qr->generated_at && is_string($qr->generated_at)) {
                    $qr->generated_at = \Carbon\Carbon::parse($qr->generated_at);
                }

                return $qr;
            });

        return view('admin.agents.qr-history', compact('agent', 'qrCodes'));
    }

    /**
     * Vô hiệu hóa một mã QR cụ thể
     */
    public function deactivateQrCode($id, $qrCodeId)
    {
        try {
            $qrCode = \App\Models\AgentQrCode::findOrFail($qrCodeId);

            // Kiểm tra xem mã QR có thuộc về đại lý này không
            if ($qrCode->agent_id != $id) {
                return redirect()->route('admin.agents.qr-history', $id)
                    ->with('error', 'Mã QR không thuộc về đại lý này.');
            }

            // Vô hiệu hóa mã QR
            $qrCode->update(['is_active' => false]);

            return redirect()->route('admin.agents.qr-history', $id)
                ->with('success', 'Đã vô hiệu hóa mã QR thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.agents.qr-history', $id)
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Kích hoạt lại một mã QR đã bị vô hiệu hóa
     */
    public function activateQrCode($id, $qrCodeId)
    {
        try {
            $qrCode = \App\Models\AgentQrCode::findOrFail($qrCodeId);

            // Kiểm tra xem mã QR có thuộc về đại lý này không
            if ($qrCode->agent_id != $id) {
                return redirect()->route('admin.agents.qr-history', $id)
                    ->with('error', 'Mã QR không thuộc về đại lý này.');
            }

            // Kích hoạt mã QR
            $qrCode->update(['is_active' => true]);

            return redirect()->route('admin.agents.qr-history', $id)
                ->with('success', 'Đã kích hoạt mã QR thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.agents.qr-history', $id)
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Vô hiệu hóa tất cả mã QR cũ của đại lý
     */
    public function deactivateAllQrCodes($id)
    {
        try {
            // Vô hiệu hóa tất cả mã QR cũ của đại lý
            \App\Models\AgentQrCode::where('agent_id', $id)->update(['is_active' => false]);

            return redirect()->route('admin.agents.qr-history', $id)
                ->with('success', 'Đã vô hiệu hóa tất cả mã QR thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.agents.qr-history', $id)
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tạo mới mã barcode cho đại lý
     */
    public function generateBarcode($id)
    {
        try {
            $success = $this->agentRepository->generateBarcode($id);

            if ($success) {
                return redirect()->route('admin.agents.barcode-history', $id)
                    ->with('success', 'Đã tạo mã barcode mới thành công.');
            } else {
                return redirect()->route('admin.agents.barcode-history', $id)
                    ->with('error', 'Không thể tạo mã barcode. Vui lòng kiểm tra log để biết thêm chi tiết.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.agents.barcode-history', $id)
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tạo mới mã barcode với mã đơn hàng
     */
    public function generateBarcodeWithOrder($id, Request $request)
    {
        $request->validate([
            'order_code' => 'required|string|max:20',
        ]);

        try {
            $orderCode = $request->input('order_code');
            $success = $this->agentRepository->generateBarcode($id, $orderCode);

            if ($success) {
                return redirect()->route('admin.agents.barcode-history', $id)
                    ->with('success', 'Đã tạo mã barcode với mã đơn hàng thành công.');
            } else {
                return redirect()->route('admin.agents.barcode-history', $id)
                    ->with('error', 'Không thể tạo mã barcode. Vui lòng kiểm tra log để biết thêm chi tiết.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.agents.barcode-history', $id)
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị lịch sử mã barcode của đại lý
     */
    public function barcodeHistory($id)
    {
        $agent = $this->agentRepository->findById($id);

        if (!$agent) {
            return redirect()->route('admin.agents.index')
                ->with('error', 'Không tìm thấy đại lý.');
        }

        // Lấy lịch sử mã barcode
        $barcodes = \App\Models\AgentBarcode::where('agent_id', $id)
            ->orderBy('generated_at', 'desc')
            ->get()
            ->map(function($barcode) {
                // Chuyển đổi các trường datetime thành Carbon object nếu chúng là string
                if ($barcode->generated_at && is_string($barcode->generated_at)) {
                    $barcode->generated_at = \Carbon\Carbon::parse($barcode->generated_at);
                }

                return $barcode;
            });

        return view('admin.agents.barcode-history', compact('agent', 'barcodes'));
    }

    /**
     * Vô hiệu hóa một mã barcode cụ thể
     */
    public function deactivateBarcode($id, $barcodeId)
    {
        try {
            $barcode = \App\Models\AgentBarcode::findOrFail($barcodeId);

            // Kiểm tra xem mã barcode có thuộc về đại lý này không
            if ($barcode->agent_id != $id) {
                return redirect()->route('admin.agents.barcode-history', $id)
                    ->with('error', 'Mã barcode không thuộc về đại lý này.');
            }

            // Vô hiệu hóa mã barcode
            $barcode->update(['status' => 'inactive']);

            return redirect()->route('admin.agents.barcode-history', $id)
                ->with('success', 'Đã vô hiệu hóa mã barcode thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.agents.barcode-history', $id)
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Kích hoạt lại một mã barcode đã bị vô hiệu hóa
     */
    public function activateBarcode($id, $barcodeId)
    {
        try {
            $barcode = \App\Models\AgentBarcode::findOrFail($barcodeId);

            // Kiểm tra xem mã barcode có thuộc về đại lý này không
            if ($barcode->agent_id != $id) {
                return redirect()->route('admin.agents.barcode-history', $id)
                    ->with('error', 'Mã barcode không thuộc về đại lý này.');
            }

            // Kích hoạt mã barcode
            $barcode->update(['status' => 'active']);

            return redirect()->route('admin.agents.barcode-history', $id)
                ->with('success', 'Đã kích hoạt mã barcode thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.agents.barcode-history', $id)
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * In mã barcode cho một đại lý
     */
    public function printBarcode($id, $barcodeId = null)
    {
        $agent = $this->agentRepository->findById($id);

        if (!$agent) {
            return redirect()->route('admin.agents.index')
                ->with('error', 'Không tìm thấy đại lý.');
        }

        // Nếu có chỉ định barcodeId, lấy barcode cụ thể
        if ($barcodeId) {
            $barcode = \App\Models\AgentBarcode::find($barcodeId);
            if (!$barcode || $barcode->agent_id != $id) {
                return redirect()->route('admin.agents.barcode-history', $id)
                    ->with('error', 'Không tìm thấy mã barcode này hoặc mã không thuộc về đại lý này.');
            }
        } else {
            // Lấy barcode mới nhất đang hoạt động
            $barcode = \App\Models\AgentBarcode::where('agent_id', $id)
                ->where('status', 'active')
                ->latest('generated_at')
                ->first();

            if (!$barcode) {
                return redirect()->route('admin.agents.barcode-history', $id)
                    ->with('error', 'Đại lý chưa có mã barcode nào đang hoạt động.');
            }
        }

        return view('admin.agents.print-barcode', compact('agent', 'barcode'));
    }

    /**
     * Hiển thị trang tìm kiếm mã barcode
     *
     * @return \Illuminate\View\View
     */
    public function searchBarcode()
    {
        return view('admin.agents.search');
    }

    /**
     * Xử lý tìm kiếm mã barcode
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function processBarcodeSearch(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $barcodeValue = $request->input('barcode');

        // Kiểm tra barcode có đúng định dạng EAN-13 không
        if (!preg_match('/^\d{13}$/', $barcodeValue)) {
            return view('admin.agents.search', [
                'barcode_query' => $barcodeValue,
                'error_message' => 'Mã barcode không đúng định dạng. Vui lòng nhập đúng 13 chữ số.'
            ]);
        }

        try {
            // Tìm mã barcode trong cơ sở dữ liệu
            $barcode = \App\Models\AgentBarcode::where('barcode_value', $barcodeValue)
                ->with('agent')
                ->first();

            if (!$barcode) {
                return view('admin.agents.search', [
                    'barcode_query' => $barcodeValue,
                    'error_message' => 'Không tìm thấy thông tin mã barcode trong hệ thống.'
                ]);
            }

            // Lấy thông tin đại lý
            $agent = $barcode->agent;
            if (!$agent) {
                return view('admin.agents.search', [
                    'barcode_query' => $barcodeValue,
                    'error_message' => 'Không tìm thấy thông tin đại lý liên kết với mã barcode này.'
                ]);
            }

            $data = [
                'barcode_query' => $barcodeValue,
                'barcode' => $barcode,
                'agent' => $agent,
                'metadata' => $barcode->metadata ?? [],
                'code_agent' => $barcode->metadata['code_agent'] ?? $barcode->agent_code,
                'generated_time' => $barcode->metadata['generated_time'] ?? ($barcode->generated_at ? $barcode->generated_at->format('d/m/Y H:i:s') : 'Không xác định'),
            ];

            return view('admin.agents.search', $data);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error searching barcode: ' . $e->getMessage());

            return view('admin.agents.search', [
                'barcode_query' => $barcodeValue,
                'error_message' => 'Có lỗi xảy ra khi tìm kiếm mã barcode.'
            ]);
        }
    }

    /**
     * Hiển thị chi tiết lượt quét mã QR
     */
    public function qrScanDetails($id, $qrCodeId)
    {
        $agent = $this->agentRepository->findById($id);
        if (!$agent) {
            return redirect()->route('admin.agents.index')
                ->with('error', 'Không tìm thấy đại lý.');
        }

        $qrCode = \App\Models\AgentQrCode::findOrFail($qrCodeId);

        // Kiểm tra xem mã QR có thuộc về đại lý này không
        if ($qrCode->agent_id != $id) {
            return redirect()->route('admin.agents.qr-history', $id)
                ->with('error', 'Mã QR không thuộc về đại lý này.');
        }

        // Lấy lịch sử quét với phân trang
        $scans = \App\Models\QrCodeScan::where('qr_code_id', $qrCodeId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Thống kê theo loại thiết bị
        $deviceStats = \App\Models\QrCodeScan::where('qr_code_id', $qrCodeId)
            ->selectRaw('device_type, count(*) as count')
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();

        // Thống kê theo trình duyệt
        $browserStats = \App\Models\QrCodeScan::where('qr_code_id', $qrCodeId)
            ->selectRaw('browser, count(*) as count')
            ->groupBy('browser')
            ->pluck('count', 'browser')
            ->toArray();

        return view('admin.agents.qr-scan-details', compact('agent', 'qrCode', 'scans', 'deviceStats', 'browserStats'));
    }
}
