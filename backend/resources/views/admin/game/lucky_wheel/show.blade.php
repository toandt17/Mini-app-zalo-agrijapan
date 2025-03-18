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
                    <h2 class="main-content-title tx-24 mg-b-5">Chi tiết giải thưởng vòng quay</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Sự kiện Mini Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.lucky_wheel.index') }}">Vòng quay may mắn</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết giải thưởng</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.lucky_wheel.edit', $prize->id) }}" class="btn btn-primary my-2 btn-icon-text">
                            <i class="fe fe-edit"></i> Sửa giải thưởng
                        </a>
                    </div>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-lg-4">
                    <div class="card custom-card">
                        <div class="card-body text-center">
                            @if($prize->image)
                                <img src="{{ Storage::url($prize->image) }}" alt="{{ $prize->prize_name }}" class="img-fluid rounded mb-3" style="max-height: 200px;">
                            @else
                                <div class="bg-light rounded p-4 mb-3">
                                    <i class="fe fe-image text-muted" style="font-size: 5rem;"></i>
                                    <p class="mt-2">Không có hình ảnh</p>
                                </div>
                            @endif
                            <h5 class="card-title">{{ $prize->prize_name }}</h5>
                            <div class="d-flex justify-content-center mt-3">
                                <span class="badge {{ $prize->has_reward ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                    {{ $prize->has_reward ? 'Có phần thưởng' : 'Không có phần thưởng' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Thông tin chi tiết</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mg-b-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" style="width: 200px;">ID</th>
                                            <td>{{ $prize->id }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Tên giải thưởng</th>
                                            <td>{{ $prize->prize_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Mô tả</th>
                                            <td>{{ $prize->description ?: 'Không có mô tả' }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Tỷ lệ trúng</th>
                                            <td>{{ number_format($prize->probability, 2) }}%</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Số lượng còn lại</th>
                                            <td>{{ $prize->remaining_quantity }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Có phần thưởng</th>
                                            <td>
                                                @if($prize->has_reward)
                                                    <span class="badge bg-success">Có</span>
                                                @else
                                                    <span class="badge bg-danger">Không</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Số người đã trúng</th>
                                            <td>{{ $winnersCount }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Ngày tạo</th>
                                            <td>{{ $prize->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Cập nhật lần cuối</th>
                                            <td>{{ $prize->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->

            <div class="mt-3">
                <a href="{{ route('admin.lucky_wheel.index') }}" class="btn btn-secondary">
                    <i class="fe fe-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
