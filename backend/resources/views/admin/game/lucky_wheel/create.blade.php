@extends('admin.layouts.master')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Thêm giải thưởng vòng quay mới</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Sự kiện Mini Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.lucky_wheel.index') }}">Vòng quay may mắn</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm giải thưởng mới</li>
                    </ol>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Thêm giải thưởng vòng quay mới</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.lucky_wheel.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="prize_name">Tên giải thưởng <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('prize_name') is-invalid @enderror" id="prize_name" name="prize_name" value="{{ old('prize_name') }}" placeholder="Nhập tên giải thưởng" required>
                                    @error('prize_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Nhập mô tả giải thưởng">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="probability">Tỷ lệ trúng (%) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" max="100" class="form-control @error('probability') is-invalid @enderror" id="probability" name="probability" value="{{ old('probability') }}" placeholder="Nhập tỷ lệ trúng (%)" required>
                                    <small class="form-text text-muted">Nhập số từ 0 đến 100</small>
                                    @error('probability')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="remaining_quantity">Số lượng còn lại <span class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control @error('remaining_quantity') is-invalid @enderror" id="remaining_quantity" name="remaining_quantity" value="{{ old('remaining_quantity', 0) }}" required>
                                    @error('remaining_quantity')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="d-flex align-items-center">
                                        <label for="has_reward" class="me-3 mb-0">Có phần thưởng thực:</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="has_reward" name="has_reward" value="1" {{ old('has_reward') ? 'checked' : '' }}>
                                        </div>
                                        <small class="text-muted ms-3">Đánh dấu nếu đây là giải thưởng có giá trị thực</small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="image">Hình ảnh</label>
                                    <div>
                                        <input id="image" type="file" class="form-control" name="image" accept="image/jpg, image/jpeg, image/png, image/gif" onchange="previewImage(event)">
                                        <small class="form-text text-muted">Chấp nhận các định dạng: jpg, jpeg, png, gif. Tối đa 2MB</small>
                                        @error('image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div id="imagePreviewContainer" style="position: relative; display: none;">
                                        <img id="imagePreview" src="#" alt="Preview" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px; margin-top: 10px;" />
                                        <button type="button" onclick="removeImage()" style="position: absolute; top: 0; right: 0; background: red; color: white; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer;">&times;</button>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu giải thưởng</button>
                                    <a href="{{ route('admin.lucky_wheel.index') }}" class="btn btn-light">Quay lại</a>
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

@push('styles')
<style>
    /* Form Switch */
    .form-switch {
        padding-left: 2.5em;
    }
    .form-check-input {
        width: 2em;
        height: 1em;
        margin-top: 0.25em;
    }
    .form-check-input:checked {
        background-color: #6259ca;
        border-color: #6259ca;
    }
</style>
@endpush

@push('scripts')
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            var container = document.getElementById('imagePreviewContainer');
            output.src = reader.result;
            container.style.display = 'block';
        };
        if(event.target.files && event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    function removeImage() {
        var input = document.getElementById('image');
        var output = document.getElementById('imagePreview');
        var container = document.getElementById('imagePreviewContainer');
        input.value = ''; // Clear the file input
        output.src = '#'; // Reset the image source
        container.style.display = 'none'; // Hide the preview container
    }
</script>
@endpush
