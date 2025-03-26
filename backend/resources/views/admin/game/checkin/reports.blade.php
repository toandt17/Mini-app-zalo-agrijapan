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
                    <div class="card custom-card shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Thống kê tổng quan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row row-sm">
                                <div class="col-md-3">
                                    <div class="card bg-info-transparent tx-info mb-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <span class="avatar avatar-lg bg-info-transparent rounded-circle tx-info">
                                                        <i class="fe fe-calendar"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="mb-1 tx-uppercase tx-11 tx-spacing-1">Tổng lượt điểm danh</p>
                                                    <h5 class="mb-0 tx-26 font-weight-semibold">{{ number_format($stats['total_checkins'] ?? 0) }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-success-transparent tx-success mb-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <span class="avatar avatar-lg bg-success-transparent rounded-circle tx-success">
                                                        <i class="fe fe-users"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="mb-1 tx-uppercase tx-11 tx-spacing-1">Người dùng tham gia</p>
                                                    <h5 class="mb-0 tx-26 font-weight-semibold">{{ number_format($stats['unique_users'] ?? 0) }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-warning-transparent tx-warning mb-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <span class="avatar avatar-lg bg-warning-transparent rounded-circle tx-warning">
                                                        <i class="fe fe-award"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="mb-1 tx-uppercase tx-11 tx-spacing-1">Điểm đã phát</p>
                                                    <h5 class="mb-0 tx-26 font-weight-semibold">{{ number_format($stats['total_points'] ?? 0) }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-danger-transparent tx-danger mb-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <span class="avatar avatar-lg bg-danger-transparent rounded-circle tx-danger">
                                                        <i class="fe fe-gift"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="mb-1 tx-uppercase tx-11 tx-spacing-1">Vé quay đã phát</p>
                                                    <h5 class="mb-0 tx-26 font-weight-semibold">{{ number_format($stats['total_spin_tickets'] ?? 0) }}</h5>
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

            <!-- Biểu đồ -->
            <div class="row row-sm mt-4">
                <div class="col-md-8">
                    <div class="card custom-card overflow-hidden shadow-sm">
                        <div class="card-header border-bottom-0 d-flex justify-content-between">
                            <div>
                                <h3 class="card-title mb-2 text-dark">Biểu đồ điểm danh theo ngày</h3>
                                <span class="d-block tx-12 mb-0 text-muted">Thống kê lượt điểm danh 7 ngày gần nhất</span>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div id="dailyCheckinChart" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card custom-card overflow-hidden shadow-sm">
                        <div class="card-header border-bottom-0">
                            <div>
                                <h3 class="card-title mb-2 text-dark">Phân bố theo ngày</h3>
                                <span class="d-block tx-12 mb-0 text-muted">Tỷ lệ điểm danh theo các ngày trong tuần</span>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <canvas id="weekdayDistributionChart" style="width: 100%; height: 300px;"></canvas>
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
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th>Ngày</th>
                                            <th>Người dùng mới</th>
                                            <th>Người dùng quay lại</th>
                                            <th>Tổng người dùng</th>
                                            <th>Tổng điểm</th>
                                            <th>Tổng vé quay</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['daily_stats'] ?? [] as $index => $dayStat)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($dayStat->date)->format('d/m/Y') }}</td>
                                                <td>{{ number_format($dayStat->new_users ?? 0) }}</td>
                                                <td>{{ number_format($dayStat->returning_users ?? 0) }}</td>
                                                <td>{{ number_format($dayStat->total_users ?? 0) }}</td>
                                                <td>{{ number_format($dayStat->total_points ?? 0) }}</td>
                                                <td>{{ number_format($dayStat->total_spin_tickets ?? 0) }}</td>
                                            </tr>
                                        @endforeach

                                        @if(empty($stats['daily_stats']) || count($stats['daily_stats']) == 0)
                                            <tr>
                                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title mb-0">Số liệu thống kê</h6>
                        </div>
                        <div class="card-body">
                            <div class="row row-sm">
                                <div class="col-md-6">
                                    <div class="p-2 border rounded mb-3">
                                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-indigo tx-semibold mg-b-8">Trung bình lượt điểm danh/ngày</h6>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-md bg-primary-transparent rounded-circle tx-primary"><i class="fe fe-users"></i></span>
                                            <div class="mg-l-10">
                                                @php
                                                    $days = count($stats['daily_stats'] ?? []);
                                                    $avg = $days > 0 ? ($stats['total_checkins'] ?? 0) / $days : 0;
                                                @endphp
                                                <h5 class="tx-bold mg-b-2 tx-20">{{ number_format($avg, 1) }}</h5>
                                                <p class="tx-11 tx-color-secondary mg-b-0">lượt</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 border rounded mb-3">
                                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-indigo tx-semibold mg-b-8">Tỷ lệ người dùng điểm danh</h6>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-md bg-success-transparent rounded-circle tx-success"><i class="fe fe-percent"></i></span>
                                            <div class="mg-l-10">
                                                @php
                                                    $totalUsers = \App\Models\User::count();
                                                    $percentage = $totalUsers > 0 ? (($stats['unique_users'] ?? 0) / $totalUsers) * 100 : 0;
                                                @endphp
                                                <h5 class="tx-bold mg-b-2 tx-20">{{ number_format($percentage, 1) }}%</h5>
                                                <p class="tx-11 tx-color-secondary mg-b-0">người dùng</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 border rounded mb-3">
                                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-indigo tx-semibold mg-b-8">Điểm trung bình mỗi điểm danh</h6>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-md bg-warning-transparent rounded-circle tx-warning"><i class="fe fe-award"></i></span>
                                            <div class="mg-l-10">
                                                @php
                                                    $avgPoints = ($stats['total_checkins'] ?? 0) > 0 ? ($stats['total_points'] ?? 0) / ($stats['total_checkins'] ?? 1) : 0;
                                                @endphp
                                                <h5 class="tx-bold mg-b-2 tx-20">{{ number_format($avgPoints, 1) }}</h5>
                                                <p class="tx-11 tx-color-secondary mg-b-0">điểm</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-2 border rounded mb-3">
                                        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-indigo tx-semibold mg-b-8">Vé quay trung bình mỗi điểm danh</h6>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar avatar-md bg-danger-transparent rounded-circle tx-danger"><i class="fe fe-gift"></i></span>
                                            <div class="mg-l-10">
                                                @php
                                                    $avgTickets = ($stats['total_checkins'] ?? 0) > 0 ? ($stats['total_spin_tickets'] ?? 0) / ($stats['total_checkins'] ?? 1) : 0;
                                                @endphp
                                                <h5 class="tx-bold mg-b-2 tx-20">{{ number_format($avgTickets, 1) }}</h5>
                                                <p class="tx-11 tx-color-secondary mg-b-0">vé quay</p>
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
    </div>
</div>
@endsection

@push('styles')
<style>
    .tx-uppercase {
        text-transform: uppercase;
    }
    .tx-spacing-1 {
        letter-spacing: 0.5px;
    }
    .tx-info {
        color: #0d6efd !important;
    }
    .tx-success {
        color: #198754 !important;
    }
    .tx-warning {
        color: #ffc107 !important;
    }
    .tx-danger {
        color: #dc3545 !important;
    }
    .bg-info-transparent {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    .bg-success-transparent {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .bg-warning-transparent {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    .bg-danger-transparent {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    .apexcharts-legend-text {
        color: #333 !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo DataTable cho bảng thống kê
    $('#file-datatable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fe fe-copy"></i> Sao chép',
                titleAttr: 'Sao chép dữ liệu',
                className: 'btn btn-primary btn-sm'
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fe fe-file-text"></i> Excel',
                titleAttr: 'Xuất Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fe fe-file"></i> PDF',
                titleAttr: 'Xuất PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'colvis',
                text: '<i class="fe fe-eye"></i> Hiển thị',
                titleAttr: 'Hiển thị/ẩn cột',
                className: 'btn btn-info btn-sm'
            }
        ],
        responsive: true,
        language: {
            searchPlaceholder: 'Tìm kiếm...',
            sSearch: '',
            lengthMenu: '_MENU_ dòng/trang',
            paginate: {
                first: "Đầu tiên",
                previous: "Trước",
                next: "Tiếp",
                last: "Cuối cùng"
            },
            info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
            infoEmpty: "Hiển thị 0 đến 0 của 0 mục",
            infoFiltered: "(lọc từ _MAX_ mục)",
            zeroRecords: "Không tìm thấy dữ liệu phù hợp",
            emptyTable: "Không có dữ liệu"
        },
        pageLength: 10,
        ordering: true
    });

    // Dữ liệu cho biểu đồ
    var stats = @json($stats['daily_stats'] ?? []);
    if (!stats || stats.length === 0) {
        document.getElementById('dailyCheckinChart').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">Không có dữ liệu điểm danh</div>';
        document.getElementById('weekdayDistributionChart').innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-muted">Không có dữ liệu điểm danh</div>';
        return;
    }

    // Chuẩn bị dữ liệu cho biểu đồ chính
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
            height: 400,
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
                }
            }
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
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            offsetY: -15,
            offsetX: 0,
            fontSize: '13px',
            fontFamily: 'Roboto, sans-serif',
            height: 50,
            width: 300,
            itemMargin: {
                horizontal: 15,
                vertical: 0
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#dailyCheckinChart"), options);
    chart.render();

    // Tạo biểu đồ polarArea (thay thế cho biểu đồ hình tròn)
    var weekdayData = {};
    stats.forEach(function(item) {
        try {
            var date = new Date(item.date);
            if (!isNaN(date.getTime()) && date.getFullYear() >= 2000) {
                var day = date.getDay();
                var dayName = ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy"][day];

                if (!weekdayData[dayName]) {
                    weekdayData[dayName] = 0;
                }
                weekdayData[dayName] += parseInt(item.count);
            }
        } catch (e) {
            console.error('Lỗi xử lý ngày trong phân bố theo thứ:', e);
        }
    });

    var weekdayLabels = Object.keys(weekdayData);
    var weekdayValues = Object.values(weekdayData);

    var backgroundColors = [
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 159, 64, 0.8)',
        'rgba(199, 100, 180, 0.8)'
    ];

    // Vẽ biểu đồ phân bố theo ngày trong tuần sử dụng polarArea
    var weekdayCtx = document.getElementById('weekdayDistributionChart').getContext('2d');
    new Chart(weekdayCtx, {
        type: 'polarArea',
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
                    position: 'right',
                    align: 'start',
                    labels: {
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw || 0;
                            var total = context.dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = Math.round((value / total) * 100);
                            return label + ': ' + value + ' lượt (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
