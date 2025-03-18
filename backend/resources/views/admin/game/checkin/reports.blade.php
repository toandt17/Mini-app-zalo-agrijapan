@extends('admin.layouts.master')

@section('content')
<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Báo cáo thống kê điểm danh</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Sự kiện Mini Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.checkin.index') }}">Quản lý điểm danh</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Báo cáo thống kê</li>
                    </ol>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- Bộ lọc thời gian -->
            <div class="row row-sm">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Bộ lọc thời gian</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.checkin.reports') }}" method="GET" class="row align-items-end">
                                <div class="col-md-4 form-group">
                                    <label for="start_date">Từ ngày</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="end_date">Đến ngày</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Lọc kết quả
                                    </button>
                                    <a href="{{ route('admin.checkin.reports') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo"></i> Đặt lại
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê tổng quan -->
            <div class="row row-sm mt-4">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Thống kê tổng quan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-info text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Tổng lượt điểm danh</h5>
                                            <p class="card-text display-4">{{ number_format($stats['total_checkins'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Người dùng tham gia</h5>
                                            <p class="card-text display-4">{{ number_format($stats['unique_users'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Điểm đã phát</h5>
                                            <p class="card-text display-4">{{ number_format($stats['total_points'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-danger text-white">
                                        <div class="card-body">
                                            <h5 class="card-title">Vé quay đã phát</h5>
                                            <p class="card-text display-4">{{ number_format($stats['total_spin_tickets'] ?? 0) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ -->
            <div class="row row-sm mt-4">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Biểu đồ điểm danh theo ngày</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="dailyCheckinChart" style="width: 100%; height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê chi tiết -->
            <div class="row row-sm mt-4">
                <div class="col-md-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Thống kê theo ngày</h6>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Số lượt điểm danh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['daily_stats'] ?? [] as $dayStat)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($dayStat->date)->format('d/m/Y') }}</td>
                                            <td>{{ number_format($dayStat->count) }}</td>
                                        </tr>
                                    @endforeach

                                    @if(empty($stats['daily_stats']) || count($stats['daily_stats']) == 0)
                                        <tr>
                                            <td colspan="2" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Số liệu thống kê</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6>Trung bình lượt điểm danh/ngày</h6>
                                        <p class="h3">
                                            @php
                                                $days = count($stats['daily_stats'] ?? []);
                                                $avg = $days > 0 ? ($stats['total_checkins'] ?? 0) / $days : 0;
                                            @endphp
                                            {{ number_format($avg, 1) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6>Tỷ lệ người dùng điểm danh</h6>
                                        <p class="h3">
                                            @php
                                                $totalUsers = \App\Models\User::count();
                                                $percentage = $totalUsers > 0 ? (($stats['unique_users'] ?? 0) / $totalUsers) * 100 : 0;
                                            @endphp
                                            {{ number_format($percentage, 1) }}%
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6>Điểm trung bình mỗi điểm danh</h6>
                                        <p class="h3">
                                            @php
                                                $avgPoints = ($stats['total_checkins'] ?? 0) > 0 ? ($stats['total_points'] ?? 0) / ($stats['total_checkins'] ?? 1) : 0;
                                            @endphp
                                            {{ number_format($avgPoints, 1) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6>Vé quay trung bình mỗi điểm danh</h6>
                                        <p class="h3">
                                            @php
                                                $avgTickets = ($stats['total_checkins'] ?? 0) > 0 ? ($stats['total_spin_tickets'] ?? 0) / ($stats['total_checkins'] ?? 1) : 0;
                                            @endphp
                                            {{ number_format($avgTickets, 1) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dữ liệu cho biểu đồ
    var stats = @json($stats['daily_stats'] ?? []);
    var labels = stats.map(function(item) { return item.date; });
    var data = stats.map(function(item) { return item.count; });

    // Vẽ biểu đồ
    var ctx = document.getElementById('dailyCheckinChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Số lượt điểm danh',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Thống kê điểm danh theo ngày'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Lượt điểm danh: ' + context.raw;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
