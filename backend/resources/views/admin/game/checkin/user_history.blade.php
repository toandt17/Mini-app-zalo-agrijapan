@extends('admin.layouts.master')

@section('content')
<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Lịch sử điểm danh người dùng</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Sự kiện Mini Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.checkin.index') }}">Quản lý điểm danh</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.checkin.history') }}">Lịch sử điểm danh</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $user->name ?? 'Chi tiết người dùng' }}</li>
                    </ol>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- Thông tin người dùng -->
            <div class="row row-sm">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 text-center">
                                    <div class="mb-3">
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                        @else
                                            <div class="avatar bg-primary rounded-circle d-flex justify-content-center align-items-center" style="width: 100px; height: 100px; margin: 0 auto;">
                                                <span class="text-white display-4">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h4 class="mb-3">{{ $user->name }}</h4>
                                    <div class="mb-2"><strong>ID:</strong> {{ $user->id }}</div>
                                    <div class="mb-2"><strong>Email:</strong> {{ $user->email ?? 'Không có' }}</div>
                                    <div class="mb-2"><strong>Điện thoại:</strong> {{ $user->phone ?? 'Không có' }}</div>
                                    <div class="mb-2"><strong>Zalo ID:</strong> {{ $user->zalo_id ?? 'Không có' }}</div>
                                    <div class="mb-2"><strong>Theo dõi OA:</strong> {{ $user->followed_oa ? 'Có' : 'Không' }}</div>
                                </div>
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card bg-primary text-white">
                                                <div class="card-body text-center p-3">
                                                    <h3 class="mb-0">{{ number_format($user->points ?? 0) }}</h3>
                                                    <small>Tổng điểm</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-success text-white">
                                                <div class="card-body text-center p-3">
                                                    <h3 class="mb-0">{{ $checkins->total() }}</h3>
                                                    <small>Lần điểm danh</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="card bg-info text-white">
                                                <div class="card-body text-center p-3">
                                                    <h3 class="mb-0">{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('d/m/Y H:i:s') : 'Chưa đăng nhập' }}</h3>
                                                    <small>Đăng nhập gần nhất</small>
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

            <!-- Bộ lọc -->
            <div class="row row-sm mt-4">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Bộ lọc</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.checkin.history', $user->id) }}" method="GET" class="row align-items-end">
                                <div class="col-md-4 form-group">
                                    <label for="date_from">Từ ngày</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="date_to">Đến ngày</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-4 form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Lọc kết quả
                                    </button>
                                    <a href="{{ route('admin.checkin.history', $user->id) }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo"></i> Đặt lại
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lịch sử điểm danh -->
            <div class="row row-sm mt-4">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Lịch sử điểm danh</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Ngày điểm danh</th>
                                            <th>Điểm nhận được</th>
                                            <th>Vé quay nhận được</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($checkins as $checkin)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('d/m/Y H:i:s') }}</td>
                                            <td class="text-success">+{{ number_format($checkin->points_earned) }}</td>
                                            <td class="text-warning">+{{ number_format($checkin->spin_tickets) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Người dùng chưa có lịch sử điểm danh</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê và biểu đồ -->
            <div class="row row-sm mt-4">
                <div class="col-md-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Thống kê điểm danh</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6>Tổng số lần điểm danh</h6>
                                        <p class="h3">{{ $checkins->total() }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6>Tổng điểm đã nhận</h6>
                                        <p class="h3">
                                            @php
                                                $totalPoints = $checkins->sum('points_earned');
                                            @endphp
                                            {{ number_format($totalPoints) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6>Tổng vé quay đã nhận</h6>
                                        <p class="h3">
                                            @php
                                                $totalSpinTickets = $checkins->sum('spin_tickets');
                                            @endphp
                                            {{ number_format($totalSpinTickets) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6>Điểm danh gần đây nhất</h6>
                                        <p class="h5">
                                            @php
                                                $latestCheckin = $checkins->first();
                                            @endphp
                                            {{ $latestCheckin ? \Carbon\Carbon::parse($latestCheckin->checkin_date)->format('d/m/Y H:i:s') : 'Chưa có' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Biểu đồ điểm danh</h6>
                        </div>
                        <div class="card-body text-center">
                            <div id="userCheckinChart" style="height: 250px;"></div>
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
    // Tạo dữ liệu mẫu cho biểu đồ - cần được thay thế bằng dữ liệu thực từ controller
    var dates = [];
    var points = [];
    var tickets = [];

    @foreach($checkins as $checkin)
        dates.push('{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('d/m') }}');
        points.push({{ $checkin->points_earned }});
        tickets.push({{ $checkin->spin_tickets }});
    @endforeach

    // Vẽ biểu đồ nếu có dữ liệu
    if (dates.length > 0) {
        var ctx = document.getElementById('userCheckinChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Điểm nhận được',
                        data: points,
                        borderColor: 'rgba(52, 152, 219, 1)',
                        backgroundColor: 'rgba(52, 152, 219, 0.1)',
                        borderWidth: 2,
                        yAxisID: 'y',
                        fill: true
                    },
                    {
                        label: 'Vé quay',
                        data: tickets,
                        borderColor: 'rgba(243, 156, 18, 1)',
                        backgroundColor: 'rgba(243, 156, 18, 0.1)',
                        borderWidth: 2,
                        yAxisID: 'y1',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Điểm'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Vé quay'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    } else {
        document.getElementById('userCheckinChart').innerHTML = '<div class="text-center p-5 text-muted">Không có dữ liệu để hiển thị biểu đồ</div>';
    }

    // Khởi tạo DataTable với các nút xuất dữ liệu
    var table = $('#file-datatable').DataTable({
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
});
</script>
@endpush
