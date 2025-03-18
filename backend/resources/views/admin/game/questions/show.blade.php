@extends('admin.layouts.master')

@section('title', 'Chi tiết câu hỏi')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Chi tiết câu hỏi</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Quản lý câu hỏi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center mr-2">
                        <a href="{{ route('admin.questions.index') }}">
                            <button class="btn btn-secondary" type="button">Quay lại</button>
                        </a>
                    </div>
                    <div class="justify-content-center">
                        <a href="{{ route('admin.questions.edit', $question->id) }}">
                            <button class="btn btn-warning" type="button">Chỉnh sửa</button>
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
                                <h6 class="main-content-label mb-3">Nội dung câu hỏi</h6>
                            </div>

                            <div class="alert alert-info" role="alert">
                                <h5>Câu hỏi:</h5>
                                <p class="mb-0">{{ $question->question }}</p>
                            </div>

                            <div class="row row-sm">
                                <div class="col-md-6">
                                    <div class="card custom-card">
                                        <div class="card-header">
                                            <h6 class="card-title">Các lựa chọn</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mg-b-0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="20%"><strong>Lựa chọn A:</strong></td>
                                                            <td class="{{ $question->correct_answer == 'a' ? 'text-success font-weight-bold' : '' }}">
                                                                {{ $question->option_a }}
                                                                @if($question->correct_answer == 'a')
                                                                    <span class="badge badge-success ml-2">Đáp án đúng</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Lựa chọn B:</strong></td>
                                                            <td class="{{ $question->correct_answer == 'b' ? 'text-success font-weight-bold' : '' }}">
                                                                {{ $question->option_b }}
                                                                @if($question->correct_answer == 'b')
                                                                    <span class="badge badge-success ml-2">Đáp án đúng</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Lựa chọn C:</strong></td>
                                                            <td class="{{ $question->correct_answer == 'c' ? 'text-success font-weight-bold' : '' }}">
                                                                {{ $question->option_c }}
                                                                @if($question->correct_answer == 'c')
                                                                    <span class="badge badge-success ml-2">Đáp án đúng</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Lựa chọn D:</strong></td>
                                                            <td class="{{ $question->correct_answer == 'd' ? 'text-success font-weight-bold' : '' }}">
                                                                {{ $question->option_d }}
                                                                @if($question->correct_answer == 'd')
                                                                    <span class="badge badge-success ml-2">Đáp án đúng</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card custom-card">
                                        <div class="card-header">
                                            <h6 class="card-title">Thông tin phần thưởng</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mg-b-0">
                                                    <tbody>
                                                        <tr>
                                                            <td width="30%"><strong>Cấp độ khó:</strong></td>
                                                            <td>
                                                                @if($question->difficulty_level == 1)
                                                                    <span class="badge badge-success">Dễ</span>
                                                                @elseif($question->difficulty_level == 2)
                                                                    <span class="badge badge-warning">Trung bình</span>
                                                                @else
                                                                    <span class="badge badge-danger">Khó</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Điểm thưởng:</strong></td>
                                                            <td>{{ $question->points_reward }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Vé quay:</strong></td>
                                                            <td>{{ $question->spin_tickets }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Phần thưởng:</strong></td>
                                                            <td>
                                                                @if($question->reward)
                                                                    <span class="badge badge-primary">{{ $question->reward->name }}</span>
                                                                @else
                                                                    <span class="badge badge-secondary">Không có</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Thời gian tạo:</strong></td>
                                                            <td>{{ $question->created_at->format('d/m/Y H:i:s') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Cập nhật cuối:</strong></td>
                                                            <td>{{ $question->updated_at->format('d/m/Y H:i:s') }}</td>
                                                        </tr>
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
            </div>
            <!-- END ROW -->

        </div>
    </div>
</div>

@endsection
