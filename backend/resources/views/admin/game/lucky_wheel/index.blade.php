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
                    <h2 class="main-content-title tx-24 mg-b-5">Quản lý vòng quay may mắn</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Sự kiện Mini Game</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lý vòng quay may mắn</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.lucky_wheel.create') }}" class="btn btn-primary my-2 btn-icon-text">
                            <i class="fe fe-plus"></i> Thêm giải thưởng mới
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
                                <h6 class="main-content-label mb-1">Danh sách giải thưởng vòng quay</h6>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered border-bottom">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Hình ảnh</th>
                                            <th>Tên giải thưởng</th>
                                            <th>Tỷ lệ (%)</th>
                                            <th>SL còn lại</th>
                                            <th>Có phần thưởng</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prizes as $prize)
                                        <tr>
                                            <td>{{ $prize->id }}</td>
                                            <td>
                                                @if($prize->image)
                                                    <img src="{{ Storage::url($prize->image) }}" alt="{{ $prize->prize_name }}" style="max-width: 50px; max-height: 50px;">
                                                @else
                                                    <span class="badge badge-secondary">Không có ảnh</span>
                                                @endif
                                            </td>
                                            <td>{{ $prize->prize_name }}</td>
                                            <td>{{ number_format($prize->probability, 2) }}%</td>
                                            <td>{{ $prize->remaining_quantity }}</td>
                                            <td>
                                                @if($prize->has_reward)
                                                    <span class="badge bg-success">Có</span>
                                                @else
                                                    <span class="badge bg-danger">Không</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.lucky_wheel.show', $prize->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.lucky_wheel.edit', $prize->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.lucky_wheel.destroy', $prize->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa giải thưởng này?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END ROW -->

            <!-- Phần thống kê -->
            <div class="row row-sm">
                <div class="col-md-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Thống kê vòng quay</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-info-transparent">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="main-content-label mb-1">Tổng số lượt quay</h6>
                                                    <h2 class="mb-0">{{ $statistics['total_spins'] ?? 0 }}</h2>
                                                </div>
                                                <div class="text-primary">
                                                    <i class="fa fa-sync-alt fa-3x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-success-transparent">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="main-content-label mb-1">Tổng người trúng giải</h6>
                                                    <h2 class="mb-0">{{ $statistics['total_winners'] ?? 0 }}</h2>
                                                </div>
                                                <div class="text-success">
                                                    <i class="fa fa-trophy fa-3x"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Thống kê giải thưởng</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tên giải</th>
                                            <th>Số lượng đã trúng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($statistics['prize_statistics'] ?? [] as $stat)
                                        <tr>
                                            <td>{{ $stat->prize_name }}</td>
                                            <td>{{ $stat->win_count }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Chưa có dữ liệu</td>
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
