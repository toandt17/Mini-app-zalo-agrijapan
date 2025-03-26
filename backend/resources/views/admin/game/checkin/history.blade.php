@extends('admin.layouts.master')

@section('content')
<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Lịch sử điểm danh</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Sự kiện Mini Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.checkin.index') }}">Quản lý điểm danh</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lịch sử điểm danh</li>
                    </ol>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- Bộ lọc -->
            <div class="row row-sm">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Bộ lọc</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.checkin.history') }}" method="GET" class="row align-items-end">
                                <div class="col-md-3 form-group">
                                    <label for="date_from">Từ ngày</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="date_to">Đến ngày</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="sort_field">Sắp xếp theo</label>
                                    <select class="form-control" id="sort_field" name="sort_field">
                                        <option value="checkin_date" {{ request('sort_field') == 'checkin_date' ? 'selected' : '' }}>Ngày điểm danh</option>
                                        <option value="points_earned" {{ request('sort_field') == 'points_earned' ? 'selected' : '' }}>Điểm</option>
                                        <option value="spin_tickets" {{ request('sort_field') == 'spin_tickets' ? 'selected' : '' }}>Vé quay</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="sort_direction">Thứ tự</label>
                                    <select class="form-control" id="sort_direction" name="sort_direction">
                                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                                    </select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-filter"></i> Lọc
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách lịch sử điểm danh -->
            <div class="row row-sm mt-4">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="card-title">Danh sách điểm danh</h6>
                            <div>
                                <a href="{{ route('admin.checkin.reports') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-chart-bar"></i> Xem báo cáo
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Người dùng</th>
                                            <th>Thông tin người dùng</th>
                                            <th>Ngày điểm danh</th>
                                            <th>Điểm</th>
                                            <th>Vé quay</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($checkins as $checkin)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('admin.checkin.history', $checkin->user_id) }}">
                                                    {{ $checkin->user->name ?? 'Không xác định' }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($checkin->user)
                                                    <div>ID: {{ $checkin->user->id }}</div>
                                                    <div>Email: {{ $checkin->user->email ?? 'N/A' }}</div>
                                                    <div>Zalo ID: {{ $checkin->user->zalo_id ?? 'N/A' }}</div>
                                                    <div>Điểm: {{ number_format($checkin->user->points ?? 0) }}</div>
                                                @else
                                                    <span class="text-muted">Không có thông tin</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($checkin->checkin_date)->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ number_format($checkin->points_earned) }}</td>
                                            <td>{{ number_format($checkin->spin_tickets) }}</td>
                                            <td>
                                                <a href="{{ route('admin.checkin.history', $checkin->user_id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-history"></i> Lịch sử
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không có dữ liệu điểm danh</td>
                                        </tr>
                                        @endforelse
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
@endsection

@push('scripts')
<script>
$(function() {
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
        ordering: true,
        columnDefs: [
            { orderable: false, targets: [2, 6] } // Cột thông tin người dùng và thao tác không sắp xếp được
        ]
    });
});
</script>
@endpush
