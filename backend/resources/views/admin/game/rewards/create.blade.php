@extends('admin.layouts.master')

@section('title', 'Thêm quà tặng mới')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Thêm quà tặng mới</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.rewards.index') }}">Quản lý quà tặng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
                    </ol>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-lg-12">
                    <div class="card custom-card overflow-hidden">
                        <div class="card-body">
                            <div>
                                <h6 class="main-content-label mb-1">Thông tin quà tặng</h6>
                            </div>

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form action="{{ route('admin.rewards.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Tên quà tặng <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="image">Hình ảnh</label>
                                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">Chấp nhận các định dạng JPG, PNG, GIF. Kích thước tối đa 2MB.</small>
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" id="quantity" name="quantity" class="form-control" value="{{ old('quantity', 0) }}" min="0" required>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu quà tặng</button>
                                    <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">Hủy</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->

        </div>
    </div>
</div>

@endsection
