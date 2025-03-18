@extends('admin.layouts.master')

@section('title', 'Chi tiết quà tặng')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Chi tiết quà tặng</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.rewards.index') }}">Quản lý quà tặng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.rewards.edit', $reward->id) }}" class="btn btn-primary">
                            <i class="fe fe-edit"></i> Chỉnh sửa
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
                                <h6 class="main-content-label mb-1">Thông tin quà tặng</h6>
                            </div>

                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th style="width: 200px;">ID</th>
                                            <td>{{ $reward->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tên quà tặng</th>
                                            <td>{{ $reward->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mô tả</th>
                                            <td>{{ $reward->description ?? 'Không có mô tả' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hình ảnh</th>
                                            <td>
                                                @if($reward->image)
                                                    <img src="{{ asset('storage/' . $reward->image) }}" alt="{{ $reward->name }}" style="max-width: 300px; height: auto;">
                                                @else
                                                    <span class="text-muted">Không có hình ảnh</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Số lượng</th>
                                            <td>{{ $reward->quantity }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ngày tạo</th>
                                            <td>{{ $reward->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cập nhật lần cuối</th>
                                            <td>{{ $reward->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Liên kết đến các đối tượng liên quan -->
                            <div class="row mt-4">
                                <!-- Câu hỏi sử dụng quà tặng này -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-primary text-white">
                                            <h5 class="mb-0">Câu hỏi sử dụng quà tặng này</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($reward->quizQuestions->count() > 0)
                                                <ul class="list-group">
                                                    @foreach($reward->quizQuestions as $question)
                                                        <li class="list-group-item">
                                                            <a href="{{ route('admin.questions.show', $question->id) }}">
                                                                {{ Str::limit($question->question, 50) }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">Không có câu hỏi nào sử dụng quà tặng này</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Nhiệm vụ sử dụng quà tặng này -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-success text-white">
                                            <h5 class="mb-0">Nhiệm vụ sử dụng quà tặng này</h5>
                                        </div>
                                        <div class="card-body">
                                            @if($reward->missions->count() > 0)
                                                <ul class="list-group">
                                                    @foreach($reward->missions as $mission)
                                                        <li class="list-group-item">
                                                            <a href="#">
                                                                {{ $mission->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">Không có nhiệm vụ nào sử dụng quà tặng này</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">
                                    <i class="fe fe-arrow-left"></i> Quay lại
                                </a>
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
