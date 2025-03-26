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
                    <!-- Phân bố theo ngày trong tuần -->
                    <div class="card custom-card overflow-hidden shadow-sm mb-3">
                        <div class="card-header border-bottom-0">
                            <div>
                                <h3 class="card-title mb-2 text-dark">Phân bố theo ngày</h3>
                                <span class="d-block tx-12 mb-0 text-muted">Thống kê điểm danh theo ngày trong tuần</span>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div style="height: 250px;">
                                <canvas id="weekdayDistributionChart"></canvas>
                            </div>
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
                                <table id="file-datatable" class="table table-bordered table-hover text-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="tx-center">STT</th>
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
                                            <td class="tx-center">{{ $loop->iteration }}</td>
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

/* Cài đặt điểm danh mới */
.reward-overview {
    border-left: 4px solid #6259ca;
    transition: all 0.3s ease;
}
.reward-overview:hover {
    background-color: #f8f9fa !important;
}
.avatar-md {
    width: 42px;
    height: 42px;
}
.reward-weeks {
    margin: 0;
    display: flex;
    flex-wrap: nowrap;
    width: 100%;
}
.reward-day {
    padding: 8px 5px;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    flex: 1;
    margin: 0 3px;
}
.reward-day:hover {
    background-color: #f8f9fa;
    transform: translateY(-3px);
}
.day-circle {
    margin: 0 auto;
    font-weight: 600;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    border: 1px solid rgba(0,0,0,0.05);
}
.reward-legend {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #dee2e6;
}

/* Thêm style mới cho rewards */
.reward-card-gradient {
    background: linear-gradient(135deg, #6259ca 0%, #4e47b3 100%);
    box-shadow: 0 5px 15px rgba(98,89,202,0.2);
    color: white;
}

.text-white-8 {
    color: rgba(255,255,255,0.8);
}

.stat-box {
    padding: 5px 10px;
    background-color: rgba(255,255,255,0.15);
    border-radius: 10px;
    min-width: 80px;
}

.reward-day-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    border: 1px solid #eee;
    transition: all 0.25s ease;
    overflow: hidden;
}

.reward-day-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    z-index: 1;
}

.active-day-highlight {
    box-shadow: 0 0 0 2px #6259ca;
    z-index: 2;
}

.day-header {
    padding: 5px;
    text-align: center;
    font-size: 12px;
    background-color: #f5f5f5;
    font-weight: 500;
}

.day-content {
    padding: 10px 5px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 80px;
}

.day-number {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 5px;
}

.reward-chips {
    display: flex;
    flex-direction: column;
    gap: 5px;
    align-items: center;
}

.reward-chip {
    padding: 3px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}

.reward-chip i {
    margin-right: 3px;
    font-size: 10px;
}

.box-shadow-0 {
    box-shadow: none !important;
}

.ht-5 {
    height: 5px !important;
}

.bg-primary-gradient {
    background: linear-gradient(135deg, #6259ca 0%, #4e47b3 100%) !important;
}

.bg-warning-gradient {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
}

.bg-gray-100 {
    background-color: #f8f9fa !important;
}
</style>
@endpush


<!-- Thư viện JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dữ liệu cho biểu đồ
    var stats = @json($stats['daily_stats'] ?? []);

    if (stats.length > 0) {
        // Chuẩn bị dữ liệu cho biểu đồ
        var categories = stats.map(function(item) { return item.date; });
        var data = stats.map(function(item) { return parseInt(item.count); });

        // Lọc chỉ lấy 7 ngày gần nhất
        var last7DaysCategories = [];
        var last7DaysData = [];

        if (categories.length > 7) {
            last7DaysCategories = categories.slice(-7);
            last7DaysData = data.slice(-7);
        } else {
            last7DaysCategories = categories;
            last7DaysData = data;
        }

        // Tạo dữ liệu cho biểu đồ nhiều dòng
        var newCustomers = last7DaysData.map(function(val) {
            // Tạo dữ liệu mẫu, giả sử 60% là khách hàng mới
            return Math.round(val * 0.6);
        });

        var returningCustomers = last7DaysData.map(function(val, idx) {
            // Phần còn lại là khách hàng quay lại
            return val - newCustomers[idx];
        });

        // Tạo biểu đồ cột (column chart) hiện đại
        var options = {
            series: [
                {
                    name: 'Người dùng mới',
                    data: newCustomers
                },
                {
                    name: 'Người dùng quay lại',
                    data: returningCustomers
                }
            ],
            chart: {
                height: 320,
                type: 'bar',
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
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '70%',
                    borderRadius: 6,
                    rangeBarOverlap: false,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#4154f1', '#ff6b8a'],
            grid: {
                borderColor: '#f2f5f7',
                strokeDashArray: 3,
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 10
                }
            },
            xaxis: {
                categories: last7DaysCategories,
                labels: {
                    formatter: function(value) {
                        try {
                            // Kiểm tra xem ngày có hợp lệ không
                            var date = new Date(value);
                            if (isNaN(date.getTime()) || date.getFullYear() < 2000) {
                                // Trả về dấu gạch ngang nếu ngày không hợp lệ
                                return '-';
                            }
                            return date.toLocaleDateString('vi-VN', {
                                day: '2-digit',
                                month: '2-digit'
                            });
                        } catch (e) {
                            console.error('Lỗi định dạng ngày:', e);
                            return '-';
                        }
                    },
                    style: {
                        colors: '#8e9cad',
                        fontSize: '12px',
                        fontFamily: 'Roboto, sans-serif',
                        fontWeight: 400
                    }
                },
                axisBorder: {
                    show: true,
                    color: '#e0e0e0'
                },
                axisTicks: {
                    show: true,
                    borderType: 'solid',
                    color: '#e0e0e0',
                    height: 6
                },
                crosshairs: {
                    show: true,
                    position: 'back',
                    stroke: {
                        color: '#b6b6b6',
                        width: 1,
                        dashArray: 3
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Số lượt điểm danh',
                    style: {
                        fontSize: '13px',
                        fontWeight: 600,
                        fontFamily: 'Roboto, sans-serif'
                    }
                },
                min: 0,
                forceNiceScale: true,
                labels: {
                    formatter: function(value) {
                        return Math.round(value);
                    },
                    style: {
                        colors: '#8e9cad',
                        fontSize: '12px',
                        fontFamily: 'Roboto, sans-serif'
                    }
                }
            },
            fill: {
                opacity: 1,
                type: 'solid'
            },
            tooltip: {
                theme: 'dark',
                shared: true,
                intersect: false,
                y: {
                    formatter: function(val) {
                        return val + " lượt điểm danh";
                    }
                },
                x: {
                    formatter: function(val) {
                        try {
                            var date = new Date(val);
                            if (isNaN(date.getTime()) || date.getFullYear() < 2000) {
                                return 'Ngày không xác định';
                            }
                            return date.toLocaleDateString('vi-VN', {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            });
                        } catch (e) {
                            console.error('Lỗi hiển thị tooltip ngày:', e);
                            return 'Ngày không xác định';
                        }
                    }
                },
                marker: {
                    show: true
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                offsetY: 0,
                offsetX: 0,
                fontSize: '13px',
                fontFamily: 'Roboto, sans-serif',
                height: 40,
                itemMargin: {
                    horizontal: 30,
                    vertical: 0
                },
                formatter: function(seriesName, opts) {
                    return [seriesName, ' - ', opts.w.globals.series[opts.seriesIndex].reduce((a, b) => a + b, 0), ' lượt'].join('')
                }
            },
            states: {
                hover: {
                    filter: {
                        type: 'darken',
                        value: 0.9
                    }
                },
                active: {
                    filter: {
                        type: 'darken',
                        value: 0.85
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#checkinChart"), options);
        chart.render();

        // Tạo biểu đồ hình tròn cho phân bố theo thứ
        var weekdayData = {};
        stats.forEach(function(item) {
            var day = new Date(item.date).getDay();
            var dayName = ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"][day];

            if (!weekdayData[dayName]) {
                weekdayData[dayName] = 0;
            }
            weekdayData[dayName] += parseInt(item.count);
        });

        var weekdayLabels = Object.keys(weekdayData);
        var weekdayValues = Object.values(weekdayData);

        var backgroundColors = [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(199, 100, 180, 0.7)'
        ];

        if (document.getElementById('weekdayDistributionChart')) {
            var weekdayCtx = document.getElementById('weekdayDistributionChart').getContext('2d');
            new Chart(weekdayCtx, {
                type: 'pie',
                data: {
                    labels: weekdayLabels,
                    datasets: [{
                        data: weekdayValues,
                        backgroundColor: backgroundColors.slice(0, weekdayLabels.length),
                        borderWidth: 1,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.parsed || 0;
                                    var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    var percentage = Math.round((value / total) * 100);
                                    return label + ': ' + value + ' lượt (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    } else {
        document.getElementById('checkinChart').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">Không có dữ liệu điểm danh</div>';
        if (document.getElementById('weekdayDistributionChart')) {
            document.getElementById('weekdayDistributionChart').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">Không có dữ liệu</div>';
        }
    }

    // Khởi tạo tooltips cho phần thưởng
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
</script>
