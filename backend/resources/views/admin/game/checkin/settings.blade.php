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
                            <h6 class="card-title">Cài đặt chung điểm danh</h6>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <form action="{{ route('admin.checkin.save-settings') }}" method="POST">
                                @csrf

                                <div class="rewards-container mb-4">
                                    @foreach(range(1, 7) as $day)
                                        @php
                                            $reward = collect($rewards ?? [])->firstWhere('day', $day);
                                            $points = $reward['points'] ?? 0;
                                            $spinTickets = $reward['spin_tickets'] ?? 0;
                                            $name = $reward['name'] ?? '';
                                        @endphp
                                        <div class="card mb-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">Ngày {{ $day }}</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <input type="hidden" name="rewards[{{ $day }}][day]" value="{{ $day }}">

                                                    <div class="col-md-12 mb-3">
                                                        <div class="form-group">
                                                            <label for="rewards_{{ $day }}_name">Tên phần thưởng</label>
                                                            <input type="text" class="form-control" id="rewards_{{ $day }}_name" name="rewards[{{ $day }}][name]" value="{{ old('rewards.'.$day.'.name', $name) }}" placeholder="Nhập tên phần thưởng">
                                                            <small class="form-text text-muted">Nếu để trống, tên sẽ được tạo tự động từ điểm và lượt quay</small>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="rewards_{{ $day }}_points">Điểm thưởng</label>
                                                            <input type="number" min="0" class="form-control" id="rewards_{{ $day }}_points" name="rewards[{{ $day }}][points]" value="{{ old('rewards.'.$day.'.points', $points) }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="rewards_{{ $day }}_spin_tickets">Lượt quay</label>
                                                            <input type="number" min="0" class="form-control" id="rewards_{{ $day }}_spin_tickets" name="rewards[{{ $day }}][spin_tickets]" value="{{ old('rewards.'.$day.'.spin_tickets', $spinTickets) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="form-text text-success" id="reward_preview_{{ $day }}">
                                                        @if($points > 0 && $spinTickets > 0)
                                                            {{ $points }} điểm + {{ $spinTickets }} lượt quay
                                                        @elseif($points > 0)
                                                            {{ $points }} điểm
                                                        @elseif($spinTickets > 0)
                                                            {{ $spinTickets }} lượt quay
                                                        @else
                                                            Không có phần thưởng
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu cài đặt</button>
                                    <a href="{{ route('admin.checkin.index') }}" class="btn btn-light">Quay lại</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bảng xem trước phần thưởng -->
                <div class="col-md-12 mt-4">
                    <div class="card custom-card">
                        <div class="card-header">
                            <h6 class="card-title">Bảng tổng hợp phần thưởng điểm danh</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Ngày</th>
                                            <th>Tên phần thưởng</th>
                                            <th>Điểm</th>
                                            <th>Lượt quay</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rewards ?? [] as $reward)
                                        <tr>
                                                <td>Ngày {{ $reward['day'] }}</td>
                                                <td>{{ $reward['name'] }}</td>
                                                <td>{{ $reward['points'] }}</td>
                                                <td>{{ $reward['spin_tickets'] }}</td>
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

    /* Card styles */
    .rewards-container .card {
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    }
    .rewards-container .card-header {
        border-radius: 8px 8px 0 0;
        padding: 10px 15px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Live preview của phần thưởng khi thay đổi giá trị
        $('.rewards-container input[type="number"]').on('input', function() {
            const dayId = $(this).closest('.card').find('input[type="hidden"]').val();
            updateRewardPreview(dayId);
        });

        function updateRewardPreview(day) {
            const points = parseInt($('#rewards_' + day + '_points').val()) || 0;
            const spinTickets = parseInt($('#rewards_' + day + '_spin_tickets').val()) || 0;
            let previewText = '';

            if (points > 0 && spinTickets > 0) {
                previewText = points + ' điểm + ' + spinTickets + ' lượt quay';
            } else if (points > 0) {
                previewText = points + ' điểm';
            } else if (spinTickets > 0) {
                previewText = spinTickets + ' lượt quay';
            } else {
                previewText = 'Không có phần thưởng';
            }

            $('#reward_preview_' + day).text(previewText);
        }
    });
</script>
@endpush
