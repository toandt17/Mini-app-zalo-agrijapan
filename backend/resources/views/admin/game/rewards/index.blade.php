@extends('admin.layouts.master')

@section('title', 'Quản lý quà tặng')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Quà tặng</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lý quà tặng</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.rewards.create') }}">
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
                                <h6 class="main-content-label mb-1">Danh sách quà tặng</h6>
                            </div>
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">ID</th>
                                            <th class="border-bottom-0">Tên quà tặng</th>
                                            <th class="border-bottom-0">Mô tả</th>
                                            <th class="border-bottom-0">Hình ảnh</th>
                                            <th class="border-bottom-0">Số lượng</th>
                                            <th class="border-bottom-0">Ngày tạo</th>
                                            <th class="border-bottom-0">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rewards as $reward)
                                        <tr>
                                            <td>{{ $reward->id }}</td>
                                            <td>{{ $reward->name }}</td>
                                            <td>{{ Str::limit($reward->description, 50) }}</td>
                                            <td>
                                                @if($reward->image)
                                                    <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}" style="max-width: 80px; height: auto;">
                                                @else
                                                    <span class="badge badge-secondary">Không có</span>
                                                @endif
                                            </td>
                                            <td>{{ $reward->quantity }}</td>
                                            <td>{{ $reward->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.rewards.show', $reward->id) }}" class="btn ripple btn-info"><i class="fe fe-eye"></i></a>
                                                <a href="{{ route('admin.rewards.edit', $reward->id) }}" class="btn ripple btn-success"><i class="fe fe-edit"></i></a>
                                                <form action="{{ route('admin.rewards.destroy', $reward->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn ripple btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa quà tặng này?')"><i class="fe fe-trash"></i></button>
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
