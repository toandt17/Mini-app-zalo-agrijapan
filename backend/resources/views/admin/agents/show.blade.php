@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Thông tin chi tiết đại lý</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Quản lý đại lý</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết đại lý</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.agents.index') }}" class="btn btn-white btn-icon-text my-2 me-2">
                            <i class="fe fe-list"></i>
                            <span>Danh sách đại lý</span>
                        </a>
                        <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-primary btn-icon-text my-2 me-2">
                            <i class="fe fe-edit"></i>
                            <span>Chỉnh sửa</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Chỉnh sửa
                                    </a>
                                    <a href="{{ route('admin.agents.regenerate-qr', $agent->id) }}" class="btn btn-success">
                                        <i class="fas fa-qrcode"></i> Tạo lại mã QR
                                    </a>
                                    <a href="{{ route('admin.agents.qr-history', $agent->id) }}" class="btn btn-info">
                                        <i class="fas fa-history"></i> Lịch sử QR
                                    </a>
                                    <a href="{{ route('admin.agents.barcode-history', $agent->id) }}" class="btn btn-warning">
                                        <i class="fas fa-barcode"></i> Quản lý Barcode
                                    </a>
                                    <form action="{{ route('admin.agents.delete', $agent->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đại lý này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </div>
                                <!-- Thông tin cơ bản -->
                                <div class="col-md-8">
                                    <h5 class="card-title tx-18 mb-4">Thông tin cơ bản</h5>
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th style="width:200px">Tên đại lý</th>
                                                <td>{{ $agent->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Số điện thoại</th>
                                                <td>{{ $agent->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th>Địa chỉ</th>
                                                <td>{{ $agent->full_address }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tỉnh/Thành phố</th>
                                                <td>{{ $agent->province ? $agent->province->name : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Quận/Huyện</th>
                                                <td>{{ $agent->district ? $agent->district->name : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phường/Xã</th>
                                                <td>{{ $agent->ward ? $agent->ward->name : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Vĩ độ</th>
                                                <td>{{ $agent->latitude }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kinh độ</th>
                                                <td>{{ $agent->longitude }}</td>
                                            </tr>
                                            <tr>
                                                <th>Trạng thái</th>
                                                <td>
                                                    @if($agent->status == 'active')
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @else
                                                        <span class="badge bg-danger">Không hoạt động</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Giờ mở cửa</th>
                                                <td>{{ $agent->open_hours ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Mô tả</th>
                                                <td>{{ $agent->description ?? 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Hình ảnh và QR code -->
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title tx-16 mb-3">Ảnh đại lý</h5>
                                            @if($agent->image)
                                                <img src="{{ asset('storage/' . $agent->image) }}" alt="{{ $agent->name }}" class="img-fluid rounded mb-4" style="max-height: 200px;">
                                            @else
                                                <p class="text-muted">Chưa có ảnh đại lý</p>
                                            @endif

                                            <h5 class="card-title tx-16 mb-3 mt-4">Mã QR</h5>
                                            @if($agent->qr_code)
                                                <div class="text-center">
                                                    <img src="{{ asset($agent->qr_code) }}" alt="QR Code" class="img-fluid mb-2" style="max-width: 200px;" id="agent-qrcode">
                                                    <div class="mt-2">
                                                        <a href="{{ asset($agent->qr_code) }}" download class="btn btn-outline-primary btn-sm mt-2">
                                                            <i class="fe fe-download"></i> Tải xuống
                                                        </a>
                                                        <a href="{{ route('admin.agents.print-single-qr-code', $agent->id) }}" class="btn btn-outline-secondary btn-sm mt-2" target="_blank">
                                                            <i class="fe fe-printer"></i> In mã QR
                                                        </a>
                                                        <a href="{{ route('admin.agents.regenerate-qr', $agent->id) }}" class="btn btn-outline-info btn-sm mt-2" onclick="return confirm('Bạn có chắc chắn muốn tạo lại mã QR không?')">
                                                            <i class="fe fe-refresh-cw"></i> Tạo lại
                                                        </a>
                                                        <a href="{{ route('admin.agents.qr-history', $agent->id) }}" class="btn btn-outline-dark btn-sm mt-2">
                                                            <i class="fe fe-list"></i> Lịch sử mã QR
                                                        </a>
                                                    </div>
                                                    <div class="mt-3 text-muted small">
                                                        @if($agent->qr_regeneration_count)
                                                            Số lần tạo mã QR: <span class="fw-bold">{{ $agent->qr_regeneration_count }}</span><br>
                                                        @endif
                                                        @if($agent->qr_code_generated_at)
                                                            Cập nhật lần cuối: {{ \Carbon\Carbon::parse($agent->qr_code_generated_at)->format('d/m/Y H:i:s') }}<br>
                                                        @endif

                                                        <!-- Thêm thông tin về số lần quét -->
                                                        @if(isset($latestQrCode))
                                                            <div class="mt-2 p-2 border rounded bg-light">
                                                                <div class="d-flex justify-content-between">
                                                                    <span>Tổng lượt quét:</span>
                                                                    <span class="fw-bold text-primary">{{ $latestQrCode->scan_count ?? 0 }}</span>
                                                                </div>
                                                                <div class="d-flex justify-content-between">
                                                                    <span>Số người quét khác nhau:</span>
                                                                    <span class="fw-bold text-info">{{ $latestQrCode->unique_scan_count ?? 0 }}</span>
                                                                </div>
                                                                @if($latestQrCode && $latestQrCode->last_scanned_at)
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Lần quét gần nhất:</span>
                                                                        <span class="fw-bold text-success">{{ \Carbon\Carbon::parse($latestQrCode->last_scanned_at)->format('d/m/Y H:i:s') }}</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-muted">Chưa có mã QR</p>
                                                <a href="{{ route('admin.agents.regenerate-qr', $agent->id) }}" class="btn btn-primary btn-sm">
                                                    Tạo mã QR
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bản đồ (nếu có tọa độ) -->
                            @if($agent->latitude && $agent->longitude)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="card-title tx-18 mb-3">Vị trí trên bản đồ</h5>
                                    <div id="map" style="height: 400px;"></div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->
        </div>
    </div>
</div>

@if($agent->latitude && $agent->longitude)
<!-- Thêm script Leaflet thay cho Google Maps -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    .info-window {
        min-width: 200px;
        max-width: 300px;
    }
    .info-window h5 {
        margin-bottom: 8px;
        color: #2D3748;
    }
    .info-window p {
        margin-bottom: 5px;
        font-size: 14px;
    }
    @media (max-width: 767px) {
        #map {
            height: 300px !important;
        }
    }
</style>

<script>
    // Đảm bảo bản đồ được khởi tạo sau khi trang đã tải xong
    document.addEventListener('DOMContentLoaded', function() {
        try {
            initMap();
        } catch(e) {
            handleMapError(e);
        }
    });

    // Khởi tạo bản đồ Leaflet
    function initMap() {
        // Khởi tạo bản đồ tại vị trí của đại lý
        const map = L.map('map').setView([{{ $agent->latitude }}, {{ $agent->longitude }}], 15);

        // Thêm tile layer từ OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Thêm marker cho đại lý
        const marker = L.marker([{{ $agent->latitude }}, {{ $agent->longitude }}])
            .addTo(map)
            .bindPopup(`
                <div class="info-window">
                    <h5>{{ $agent->name }}</h5>
                    <p><strong>Địa chỉ:</strong> {{ $agent->full_address }}</p>
                    <p><strong>Điện thoại:</strong> {{ $agent->phone }}</p>
                    <p><strong>Giờ mở cửa:</strong> {{ $agent->open_hours ?? 'Chưa cập nhật' }}</p>
                </div>
            `).openPopup();
    }

    // Xử lý khi không thể tải bản đồ
    function handleMapError(error) {
        console.error("Lỗi tải bản đồ:", error);
        document.getElementById("map").innerHTML =
            '<div class="alert alert-danger">Không thể tải bản đồ. Vui lòng kiểm tra kết nối mạng.</div>';
    }
</script>
@endif

<script>
    // Hàm in mã QR
    function printQRCode() {
        // Lấy URL của hình ảnh QR code
        var qrCodeSrc = document.getElementById('agent-qrcode').src;

        // Kiểm tra xem đã lấy được URL hình ảnh chưa
        if (!qrCodeSrc) {
            alert('Không thể tìm thấy hình ảnh mã QR cho đại lý này');
            return;
        }

        // Chuẩn bị nội dung để in
        var printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>In mã QR - {{ $agent->name }}</title>
                <style>
                    body {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        height: 100vh;
                        font-family: Arial, sans-serif;
                    }
                    .container {
                        text-align: center;
                    }
                    .qr-code {
                        max-width: 300px;
                        margin: 20px 0;
                    }
                    .agent-info {
                        margin-bottom: 20px;
                    }
                    @media print {
                        @page {
                            size: 80mm 80mm;
                            margin: 5mm;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="agent-info">
                        <h3>{{ $agent->name }}</h3>
                        <p>{{ $agent->phone }}</p>
                        <p>{{ $agent->full_address }}</p>
                    </div>
                    <img src="${qrCodeSrc}" alt="QR Code" class="qr-code" id="qr-image">
                    <p>Quét mã QR này để xem thông tin đại lý</p>
                </div>
                <script>
                    // Đợi hình ảnh tải xong
                    document.getElementById('qr-image').onload = function() {
                        // Đặt timeout để đảm bảo tài nguyên được tải hoàn tất
                        setTimeout(function() {
                            try {
                                window.print();
                                // window.close(); // Đóng cửa sổ sau khi in xong
                            } catch (e) {
                                console.error("Lỗi khi in:", e);
                                alert("Có lỗi khi in: " + e.message);
                            }
                        }, 500);
                    };

                    // Xử lý lỗi nếu hình ảnh không tải được
                    document.getElementById('qr-image').onerror = function() {
                        document.body.innerHTML = '<div style="color:red;text-align:center;margin:50px;">Không thể tải hình ảnh mã QR. Vui lòng thử lại!</div>';
                    };
                </script>
            </body>
            </html>
        `;
    }
</script>

@endsection
