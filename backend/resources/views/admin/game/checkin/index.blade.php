@extends('admin.layouts.master')

@section('title', 'Quản lý điểm danh')

@section('content')
<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Quản lý điểm danh</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="javascript:;">Sự kiện Mini Game</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lý điểm danh</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.checkin.settings') }}" class="btn btn-primary my-2 btn-icon-text">
                            <i class="fe fe-settings me-2"></i> Cài đặt điểm danh
                        </a>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- Thống kê tổng quan -->
            <div class="row row-sm">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card overflow-hidden bg-primary-gradient shadow-sm">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-0">
                                    <h5 class="mb-1 number-font text-white">{{ number_format($stats['total_checkins'] ?? 0) }}</h5>
                                    <p class="mb-0 text-white op-7">Tổng lượt điểm danh</p>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-0">
                                        <i class="fa fa-calendar-check fa-2x text-white-5 op-7"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.checkin.history') }}" class="small-box-footer bg-primary-transparent text-white border-top-0 py-1">
                            Chi tiết <i class="fe fe-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card overflow-hidden bg-success-gradient shadow-sm">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-0">
                                    <h5 class="mb-1 number-font text-white">{{ number_format($stats['unique_users'] ?? 0) }}</h5>
                                    <p class="mb-0 text-white op-7">Người dùng tham gia</p>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-0">
                                        <i class="fa fa-users fa-2x text-white-5 op-7"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.checkin.history') }}" class="small-box-footer bg-success-transparent text-white border-top-0 py-1">
                            Chi tiết <i class="fe fe-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card overflow-hidden bg-warning-gradient shadow-sm">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-0">
                                    <h5 class="mb-1 number-font text-white">{{ number_format($stats['total_points'] ?? 0) }}</h5>
                                    <p class="mb-0 text-white op-7">Điểm đã phát</p>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-0">
                                        <i class="fa fa-coins fa-2x text-white-5 op-7"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.checkin.reports') }}" class="small-box-footer bg-warning-transparent text-white border-top-0 py-1">
                            Chi tiết <i class="fe fe-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                    <div class="card overflow-hidden bg-danger-gradient shadow-sm">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="mt-0">
                                    <h5 class="mb-1 number-font text-white">{{ number_format($stats['total_spin_tickets'] ?? 0) }}</h5>
                                    <p class="mb-0 text-white op-7">Vé quay đã phát</p>
                                </div>
                                <div class="ms-auto">
                                    <div class="chart-wrapper mt-0">
                                        <i class="fa fa-ticket-alt fa-2x text-white-5 op-7"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.checkin.reports') }}" class="small-box-footer bg-danger-transparent text-white border-top-0 py-1">
                            Chi tiết <i class="fe fe-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row row-sm">
                <!-- Biểu đồ điểm danh -->
                <div class="col-lg-8 col-md-12 mb-3">
                    <div class="card custom-card overflow-hidden shadow-sm">
                        <div class="card-header border-bottom-0 d-flex justify-content-between">
                            <div>
                                <h3 class="card-title mb-2 text-dark">Biểu đồ điểm danh 30 ngày qua</h3>
                                <span class="d-block tx-12 mb-0 text-muted">Thống kê lượt điểm danh hàng ngày</span>
                            </div>
                            <div class="card-options">
                                <a href="{{ route('admin.checkin.reports') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fe fe-bar-chart-2 me-1"></i> Thống kê chi tiết
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div id="checkinChart" style="height: 320px;"></div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar information -->
                <div class="col-lg-4 col-md-12">
                    <!-- Cài đặt nhanh -->
                    <div class="card custom-card overflow-hidden shadow-sm mb-3">
                        <div class="card-header border-bottom-0 d-flex justify-content-between">
                            <div>
                                <h3 class="card-title mb-2 text-dark">Cài đặt điểm danh</h3>
                                <span class="d-block tx-12 mb-0 text-muted">Cấu hình phần thưởng hiện tại</span>
                            </div>
                            <div class="card-options">
                                <a href="{{ route('admin.checkin.settings') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fe fe-settings me-1"></i> Sửa
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            @php
                                $settings = $settings ?? [];
                                $pointsReward = $settings['points_reward'] ?? 10;
                                $spinTicketsReward = $settings['spin_tickets_reward'] ?? 1;
                                $consecutiveBonus = $settings['consecutive_bonus'] ?? true;
                            @endphp

                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-top-0">
                                    <div>
                                        <i class="fa fa-coins text-primary me-2"></i>
                                        Điểm thưởng mỗi lần điểm danh
                                    </div>
                                    <span class="badge bg-primary rounded-pill">{{ $pointsReward }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                    <div>
                                        <i class="fa fa-ticket-alt text-warning me-2"></i>
                                        Vé quay thưởng mỗi lần điểm danh
                                    </div>
                                    <span class="badge bg-warning rounded-pill">{{ $spinTicketsReward }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-bottom-0">
                                    <div>
                                        <i class="fa fa-calendar-week text-success me-2"></i>
                                        Thưởng điểm danh liên tục
                                    </div>
                                    <span class="badge {{ $consecutiveBonus ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                        {{ $consecutiveBonus ? 'Bật' : 'Tắt' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Thông tin nhanh -->
                    <div class="card custom-card overflow-hidden shadow-sm">
                        <div class="card-header border-bottom-0">
                            <div>
                                <h3 class="card-title mb-2 text-dark">Thống kê nhanh</h3>
                                <span class="d-block tx-12 mb-0 text-muted">Số liệu điểm danh hôm nay</span>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="mb-4">
                                <label class="form-label mb-1 text-muted">Điểm danh hôm nay</label>
                                <div class="progress ht-20 mb-1">
                                    @php
                                        $todayCheckins = isset($stats['daily_stats']) ?
                                            $stats['daily_stats']->where('date', now()->format('Y-m-d'))->first()->count ?? 0 : 0;
                                        $maxDailyCheckins = isset($stats['daily_stats']) && $stats['daily_stats']->count() > 0 ?
                                            $stats['daily_stats']->max('count') : 1;
                                        $percent = min(100, round(($todayCheckins / max(1, $maxDailyCheckins)) * 100));
                                    @endphp
                                    <div class="progress-bar bg-primary" style="width: {{ $percent }}%">
                                        {{ $todayCheckins }}
                                    </div>
                                </div>
                                <small class="text-muted">{{ $percent }}% so với ngày cao nhất</small>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div>
                                    <h6 class="mb-1 fw-semibold">Tổng số điểm danh 7 ngày qua</h6>
                                    @php
                                        $last7DaysCheckins = isset($stats['daily_stats']) ?
                                            $stats['daily_stats']->filter(function($item) {
                                                return \Carbon\Carbon::parse($item->date)->gte(now()->subDays(7));
                                            })->sum('count') : 0;
                                    @endphp
                                    <h4 class="mb-0 fs-20 number-font">{{ number_format($last7DaysCheckins) }}</h4>
                                </div>
                                <div class="ms-auto">
                                    <span class="badge bg-success-transparent rounded-pill px-3 py-2">
                                        <i class="fe fe-trending-up me-1"></i> Tuần này
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="d-grid">
                                    <a href="{{ route('admin.checkin.reports') }}" class="btn btn-outline-primary">
                                        <i class="fe fe-bar-chart-2 me-1"></i> Xem báo cáo đầy đủ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lịch sử điểm danh gần đây -->
            <div class="row row-sm">
                <div class="col-md-12">
                    <div class="card custom-card shadow-sm">
                        <div class="card-header border-bottom-0 d-flex justify-content-between">
                            <div>
                                <h3 class="card-title mb-2 text-dark">Lịch sử điểm danh gần đây</h3>
                                <span class="d-block tx-12 mb-0 text-muted">Các lượt điểm danh mới nhất trong hệ thống</span>
                            </div>
                            <div class="card-options">
                                <a href="{{ route('admin.checkin.history') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fe fe-list me-1"></i> Xem tất cả
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-2">
                            <div class="table-responsive">
                                <table class="table table-hover border text-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="tx-center">ID</th>
                                            <th>Người dùng</th>
                                            <th>Ngày điểm danh</th>
                                            <th class="tx-center">Điểm</th>
                                            <th class="tx-center">Vé quay</th>
                                            <th class="tx-center">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentCheckins as $checkin)
                                        <tr>
                                            <td class="tx-center">{{ $checkin->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        @if(isset($checkin->user) && $checkin->user->avatar)
                                                            <img src="{{ $checkin->user->avatar }}" class="rounded-circle" alt="">
                                                        @else
                                                            <span class="avatar-initial rounded-circle bg-primary">
                                                                {{ isset($checkin->user) ? substr($checkin->user->name, 0, 1) : 'U' }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('admin.checkin.history', $checkin->user_id) }}" class="text-dark fw-semibold d-block mb-0 tx-15">
                                                            {{ $checkin->user->name ?? 'Người dùng #' . $checkin->user_id }}
                                                        </a>
                                                        <span class="tx-11 text-muted">
                                                            {{ isset($checkin->user) ? ($checkin->user->email ?? $checkin->user->phone ?? 'ID: ' . $checkin->user->id) : '' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-block tx-14">{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('d/m/Y H:i:s') }}</span>
                                                <span class="tx-11 text-muted">{{ \Carbon\Carbon::parse($checkin->checkin_date)->diffForHumans() }}</span>
                                            </td>
                                            <td class="tx-center text-success fw-semibold">
                                                +{{ number_format($checkin->points_earned) }}
                                            </td>
                                            <td class="tx-center text-warning fw-semibold">
                                                +{{ number_format($checkin->spin_tickets) }}
                                            </td>
                                            <td class="tx-center">
                                                <a href="{{ route('admin.checkin.history', $checkin->user_id) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                                    <i class="fe fe-user me-1"></i> Chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <span class="text-muted d-block mb-2"><i class="fe fe-alert-circle me-1"></i> Không có dữ liệu điểm danh</span>
                                                <small class="text-muted">Chưa có người dùng nào điểm danh trong hệ thống</small>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent d-flex justify-content-between">
                            <a href="{{ route('admin.checkin.history') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                <i class="fe fe-list-ul me-1"></i> Tất cả lịch sử điểm danh
                            </a>
                            <a href="{{ route('admin.checkin.reports') }}" class="btn btn-info btn-sm rounded-pill px-3">
                                <i class="fe fe-bar-chart-2 me-1"></i> Báo cáo thống kê
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.text-white-5 {
    opacity: 0.5;
}
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.table th, .table td {
    vertical-align: middle;
}
.rounded-pill {
    border-radius: 50rem !important;
}
.bg-primary-transparent {
    background-color: rgba(98, 89, 202, 0.1) !important;
}
.bg-success-transparent {
    background-color: rgba(26, 156, 68, 0.1) !important;
}
.bg-warning-transparent {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
.bg-danger-transparent {
    background-color: rgba(220, 53, 69, 0.1) !important;
}
.bg-info-transparent {
    background-color: rgba(13, 202, 240, 0.1) !important;
}
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}
.op-7 {
    opacity: 0.7;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dữ liệu cho biểu đồ
    var stats = @json($stats['daily_stats'] ?? []);
    var categories = stats.map(function(item) { return item.date; });
    var data = stats.map(function(item) { return item.count; });

    // Tạo biểu đồ với ApexCharts
    var options = {
        series: [{
            name: 'Lượt điểm danh',
            data: data
        }],
        chart: {
            height: 320,
            type: 'area',
            fontFamily: 'Roboto, sans-serif',
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#6259ca'],
        grid: {
            borderColor: '#f2f5f7',
            strokeDashArray: 3,
            padding: {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            }
        },
        xaxis: {
            categories: categories,
            labels: {
                formatter: function(value) {
                    return new Date(value).toLocaleDateString('vi-VN', {
                        day: '2-digit',
                        month: '2-digit'
                    });
                },
                style: {
                    colors: '#8e9cad',
                    fontSize: '12px'
                }
            },
            axisBorder: {
                show: true,
                color: 'rgba(119, 119, 142, 0.05)',
                offsetX: 0,
                offsetY: 0,
            },
            axisTicks: {
                show: true,
                borderType: 'solid',
                color: 'rgba(119, 119, 142, 0.05)',
                width: 6,
                offsetX: 0,
                offsetY: 0
            }
        },
        yaxis: {
            title: {
                text: 'Số lượt điểm danh',
                style: {
                    color: '#8e9cad',
                    fontSize: '12px',
                    fontFamily: 'Roboto, sans-serif',
                    fontWeight: 600
                }
            },
            labels: {
                formatter: function(value) {
                    return value.toFixed(0);
                },
                style: {
                    colors: '#8e9cad',
                    fontSize: '12px'
                }
            },
            min: 0
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "vertical",
                shadeIntensity: 0.5,
                gradientToColors: undefined,
                inverseColors: true,
                opacityFrom: 0.6,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        tooltip: {
            theme: 'dark',
            y: {
                formatter: function(val) {
                    return val + " lượt điểm danh"
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            offsetY: -20,
            fontSize: '13px',
            fontFamily: 'Roboto, sans-serif',
            markers: {
                width: 10,
                height: 10,
                strokeWidth: 0,
                radius: 12
            },
            itemMargin: {
                horizontal: 10,
                vertical: 0
            }
        }
    };

    if (stats.length > 0) {
        var chart = new ApexCharts(document.querySelector("#checkinChart"), options);
        chart.render();
    } else {
        document.getElementById('checkinChart').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">Không có dữ liệu điểm danh trong 30 ngày qua</div>';
    }
});
</script>
@endpush
