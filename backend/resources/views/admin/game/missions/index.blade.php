@extends('admin.layouts.master')

@section('title', 'Quản lý nhiệm vụ')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Nhiệm vụ</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lý nhiệm vụ</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.missions.create') }}">
                            <button class="btn btn-primary" type="submit">Thêm mới</button>
                        </a>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-1">Danh sách nhiệm vụ</h6>

                                @if (session('success'))
                                    <div class="alert alert-success mt-2">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger mt-2">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">ID</th>
                                            <th class="border-bottom-0">Tên nhiệm vụ</th>
                                            <th class="border-bottom-0">Mô tả</th>
                                            <th class="border-bottom-0">Điểm thưởng</th>
                                            <th class="border-bottom-0">Vé quay</th>
                                            <th class="border-bottom-0">Phần thưởng</th>
                                            <th class="border-bottom-0">Tỷ lệ hoàn thành</th>
                                            <th class="border-bottom-0">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($missions as $mission)
                                        <tr>
                                            <td>{{ $mission->id }}</td>
                                            <td>{{ $mission->name }}</td>
                                            <td>{{ Str::limit($mission->description, 50) }}</td>
                                            <td>{{ $mission->points_reward }}</td>
                                            <td>{{ $mission->spin_tickets }}</td>
                                            <td>
                                                @if($mission->reward)
                                                    <span class="badge badge-primary">{{ $mission->reward->name }}</span>
                                                @else
                                                    <span class="badge badge-secondary">Không có</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="progress mg-b-10">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: {{ $mission->completion_percentage }}%"
                                                        aria-valuenow="{{ $mission->completion_percentage }}" aria-valuemin="0" aria-valuemax="100">
                                                        {{ $mission->completion_percentage }}%
                                                    </div>
                                                </div>
                                                <small>{{ $mission->completion_count }} người hoàn thành</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.missions.show', $mission->id) }}" class="btn ripple btn-info"><i class="fe fe-eye"></i></a>
                                                <a href="{{ route('admin.missions.edit', $mission->id) }}" class="btn ripple btn-success"><i class="fe fe-edit"></i></a>
                                                <form action="{{ route('admin.missions.destroy', $mission->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn ripple btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa nhiệm vụ này?')"><i class="fe fe-trash"></i></button>
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

@endsection
