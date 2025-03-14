@extends('admin.layouts.master')
@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Danh sách đại lý</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Quản lý đại lý</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách đại lý</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.agents.add') }}" class="btn btn-primary my-2 btn-icon-text">
                            <i class="fe fe-plus"></i> Thêm mới đại lý
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
                            <div>
                                <h6 class="main-content-label mb-1">Danh sách đại lý</h6>
                            </div>
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tên đại lý</th>
                                            <th>Địa chỉ</th>
                                            <th>Số điện thoại</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($agents as $agent)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $agent->name }}</td>
                                                <td>{{ $agent->full_address }}</td>
                                                <td>{{ $agent->phone }}</td>
                                                <td>
                                                    @if($agent->status == 'active')
                                                        <span class="badge bg-success">Hoạt động</span>
                                                    @else
                                                        <span class="badge bg-danger">Không hoạt động</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-success btn-sm">Sửa</a>
                                                    <form action="{{ route('admin.agents.delete', $agent->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                                    </form>
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
            <!-- END ROW -->
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Bạn có chắc chắn muốn xóa đại lý này không?');
    }
</script>

@endsection
