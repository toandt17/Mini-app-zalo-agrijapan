@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Chi tiết lượt quét mã QR - {{ $agent->name }}</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.agents.index') }}">Quản lý đại lý</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.agents.qr-history', $agent->id) }}">Lịch sử mã QR</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết lượt quét</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.agents.qr-history', $agent->id) }}" class="btn btn-white btn-icon-text my-2 me-2">
                            <i class="fe fe-arrow-left"></i>
                            <span>Quay lại</span>
                        </a>
                        <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-primary btn-icon-text my-2 me-2">
                            <i class="fe fe-info"></i>
                            <span>Thông tin đại lý</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- THÔNG TIN MÃ QR -->
            <div class="row row-sm">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    @if($qrCode->qr_code_path)
                                        <img src="{{ asset($qrCode->qr_code_path) }}" alt="QR Code" class="img-fluid" style="max-width: 150px;">
                                        <div class="mt-2">
                                            <a href="{{ asset($qrCode->qr_code_path) }}" download class="btn btn-sm btn-outline-primary">
                                                <i class="fe fe-download"></i> Tải xuống
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h6 class="main-content-label mb-3">Thông tin mã QR</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-striped table-sm">
                                                <tbody>
                                                    <tr>
                                                        <th>Mã token:</th>
                                                        <td>{{ $qrCode->qr_token }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Ngày tạo:</th>
                                                        <td>{{ $qrCode->generated_at->format('d/m/Y H:i:s') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Trạng thái:</th>
                                                        <td>
                                                            @if($qrCode->is_active)
                                                                <span class="badge bg-success">Đang hoạt động</span>
                                                            @else
                                                                <span class="badge bg-danger">Đã vô hiệu hóa</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-striped table-sm">
                                                <tbody>
                                                    <tr>
                                                        <th>Tổng lượt quét:</th>
                                                        <td><span class="badge bg-primary">{{ $qrCode->scan_count ?? 0 }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Số người quét:</th>
                                                        <td><span class="badge bg-info">{{ $qrCode->unique_scan_count ?? 0 }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Lần quét gần nhất:</th>
                                                        <td>
                                                            @if($qrCode->last_scanned_at)
                                                                {{ \Carbon\Carbon::parse($qrCode->last_scanned_at)->format('d/m/Y H:i:s') }}
                                                            @else
                                                                <span class="text-muted">Chưa có lượt quét</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- THỐNG KÊ LƯỢT QUÉT -->
            <div class="row row-sm">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-3">Thống kê lượt quét</h6>
                            </div>

                            <div class="row row-sm">
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body p-3 bg-light">
                                            <h3 class="mb-1 text-primary">{{ $deviceStats['mobile'] ?? 0 }}</h3>
                                            <span>Di động</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body p-3 bg-light">
                                            <h3 class="mb-1 text-primary">{{ $deviceStats['desktop'] ?? 0 }}</h3>
                                            <span>Máy tính</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card text-center">
                                        <div class="card-body p-3 bg-light">
                                            <h3 class="mb-1 text-success">{{ ($deviceStats['tablet'] ?? 0) + ($deviceStats['mobile'] ?? 0) + ($deviceStats['desktop'] ?? 0) }}</h3>
                                            <span>Tổng số</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h6 class="main-content-label mb-3">Lượt quét theo trình duyệt</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div style="height: 350px; width: 100%;">
                                            <canvas id="browserChart" height="350" width="350"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Trình duyệt</th>
                                                        <th>Số lượt quét</th>
                                                        <th>Tỷ lệ</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $totalScans = array_sum($browserStats);
                                                    @endphp
                                                    @foreach($browserStats as $browser => $count)
                                                        <tr>
                                                            <td>{{ $browser }}</td>
                                                            <td>{{ $count }}</td>
                                                            <td>
                                                                @if($totalScans > 0)
                                                                    {{ number_format(($count / $totalScans) * 100, 1) }}%
                                                                @else
                                                                    0%
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DANH SÁCH LƯỢT QUÉT -->
            <div class="row row-sm">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-1">Lịch sử quét mã QR</h6>
                                <p class="text-muted card-sub-title">Danh sách các lượt quét của mã QR này</p>
                            </div>
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Thời gian</th>
                                            <th>Địa chỉ IP</th>
                                            <th>Thiết bị</th>
                                            <th>Trình duyệt</th>
                                            <th>Device Fingerprint</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($scans->count() > 0)
                                            @foreach($scans as $index => $scan)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $scan->created_at->format('d/m/Y H:i:s') }}</td>
                                                    <td>{{ $scan->ip_address }}</td>
                                                    <td>
                                                        @if($scan->device_type == 'mobile')
                                                            <span class="badge bg-primary">Di động</span>
                                                        @elseif($scan->device_type == 'tablet')
                                                            <span class="badge bg-info">Máy tính bảng</span>
                                                        @else
                                                            <span class="badge bg-secondary">Máy tính</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $scan->browser }}</td>
                                                    <td><small class="text-muted">{{ substr($scan->device_fingerprint ?? 'N/A', 0, 8) }}...</small></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">Chưa có lượt quét nào</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Khởi tạo biểu đồ cho trình duyệt
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('browserChart').getContext('2d');

        var browserData = @json($browserStats);
        var labels = Object.keys(browserData);
        var data = Object.values(browserData);

        var backgroundColors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)'
        ];

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors.slice(0, labels.length)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>

@endsection