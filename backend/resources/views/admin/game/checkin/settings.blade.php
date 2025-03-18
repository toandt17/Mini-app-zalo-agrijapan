@extends('admin.layouts.master')

@section('content')
<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Cài đặt điểm danh</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Sự kiện Mini Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.checkin.index') }}">Quản lý điểm danh</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cài đặt điểm danh</li>
                    </ol>
                </div>
            </div>
            <!-- END PAGE HEADER -->

            <!-- ROW -->
            <div class="row row-sm">
                <div class="col-md-12">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Cài đặt phần thưởng điểm danh</h6>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('admin.checkin.save-settings') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="points_reward">Điểm thưởng mỗi lần điểm danh <span class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control @error('points_reward') is-invalid @enderror" id="points_reward" name="points_reward" value="{{ old('points_reward', $settings['points_reward'] ?? 10) }}" required>
                                    <small class="form-text text-muted">Số điểm người dùng nhận được cho mỗi lần điểm danh</small>
                                    @error('points_reward')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="spin_tickets_reward">Vé quay thưởng mỗi lần điểm danh <span class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control @error('spin_tickets_reward') is-invalid @enderror" id="spin_tickets_reward" name="spin_tickets_reward" value="{{ old('spin_tickets_reward', $settings['spin_tickets_reward'] ?? 1) }}" required>
                                    <small class="form-text text-muted">Số vé quay thưởng người dùng nhận được cho mỗi lần điểm danh</small>
                                    @error('spin_tickets_reward')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <div class="d-flex align-items-center">
                                        <label for="consecutive_bonus" class="me-3 mb-0">Thưởng điểm danh liên tục:</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="consecutive_bonus" name="consecutive_bonus" value="1" {{ (old('consecutive_bonus', $settings['consecutive_bonus'] ?? true)) ? 'checked' : '' }}>
                                        </div>
                                        <small class="text-muted ms-3">Bật để thưởng thêm điểm cho người dùng điểm danh nhiều ngày liên tiếp</small>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu cài đặt</button>
                                    <a href="{{ route('admin.checkin.index') }}" class="btn btn-light">Quay lại</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cài đặt nâng cao -->
                <div class="col-md-12 mt-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Cài đặt điểm danh nâng cao</h6>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h5><i class="fas fa-info-circle"></i> Thông tin thưởng điểm danh liên tục</h5>
                                <p class="mb-0">Khi bật tính năng thưởng điểm danh liên tục, người dùng sẽ nhận được phần thưởng tăng dần theo số ngày điểm danh liên tiếp:</p>
                                <ul class="mt-2 mb-0">
                                    <li>Ngày 1: 100% điểm thưởng cơ bản</li>
                                    <li>Ngày 2-3: 120% điểm thưởng cơ bản</li>
                                    <li>Ngày 4-6: 150% điểm thưởng cơ bản</li>
                                    <li>Ngày 7+: 200% điểm thưởng cơ bản</li>
                                </ul>
                            </div>

                            <div class="table-responsive mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Điểm danh liên tục</th>
                                            <th>Điểm thưởng</th>
                                            <th>Vé quay thưởng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $basePoints = $settings['points_reward'] ?? 10;
                                            $baseTickets = $settings['spin_tickets_reward'] ?? 1;
                                            $bonusEnabled = $settings['consecutive_bonus'] ?? true;
                                        @endphp
                                        <tr>
                                            <td>Ngày 1</td>
                                            <td>{{ $basePoints }}</td>
                                            <td>{{ $baseTickets }}</td>
                                        </tr>
                                        <tr>
                                            <td>Ngày 2-3</td>
                                            <td>{{ $bonusEnabled ? round($basePoints * 1.2) : $basePoints }}</td>
                                            <td>{{ $baseTickets }}</td>
                                        </tr>
                                        <tr>
                                            <td>Ngày 4-6</td>
                                            <td>{{ $bonusEnabled ? round($basePoints * 1.5) : $basePoints }}</td>
                                            <td>{{ $bonusEnabled ? $baseTickets + 1 : $baseTickets }}</td>
                                        </tr>
                                        <tr>
                                            <td>Ngày 7+</td>
                                            <td>{{ $bonusEnabled ? round($basePoints * 2) : $basePoints }}</td>
                                            <td>{{ $bonusEnabled ? $baseTickets + 2 : $baseTickets }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <div class="alert alert-secondary">
                                    <i class="fe fe-info me-2"></i>
                                    Cài đặt được lưu trong file cấu hình: <code>config/checkin_settings.json</code>
                                </div>
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
