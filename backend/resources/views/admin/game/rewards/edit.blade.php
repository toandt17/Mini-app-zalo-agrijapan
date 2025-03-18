@extends('admin.layouts.master')

@section('title', 'Chỉnh sửa quà tặng')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Chỉnh sửa quà tặng</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.rewards.index') }}">Quản lý quà tặng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
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

                            <form action="{{ route('admin.rewards.update', $reward->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="name">Tên quà tặng <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $reward->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $reward->description) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="image">Hình ảnh</label>
                                    @if($reward->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}" style="max-width: 200px; height: auto;" class="mb-2">
                                            <p class="text-muted">Hình ảnh hiện tại</p>
                                        </div>
                                    @endif
                                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">Để trống nếu không muốn thay đổi hình ảnh. Chấp nhận các định dạng JPG, PNG, GIF. Kích thước tối đa 2MB.</small>
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Số lượng <span class="text-danger">*</span></label>
                                    <input type="number" id="quantity" name="quantity" class="form-control" value="{{ old('quantity', $reward->quantity) }}" min="0" required>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
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
