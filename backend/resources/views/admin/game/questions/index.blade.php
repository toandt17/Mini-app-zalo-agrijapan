@extends('admin.layouts.master')

@section('title', 'Quản lý câu hỏi')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Câu hỏi Quiz</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quản lý câu hỏi</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.questions.create') }}">
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
                                <h6 class="main-content-label mb-1">Danh sách câu hỏi</h6>

                                <!-- Bộ lọc theo cấp độ -->
                                <div class="my-3">
                                    <form action="{{ route('admin.questions.filter') }}" method="GET" class="form-inline">
                                        <div class="form-group mr-3">
                                            <label class="mr-2">Lọc theo cấp độ:</label>
                                            <select name="level" class="form-control" onchange="this.form.submit()">
                                                <option value="">Tất cả cấp độ</option>
                                                @foreach($difficultyLevels as $key => $level)
                                                    <option value="{{ $key }}" {{ isset($level) && $level == $key ? 'selected' : '' }}>
                                                        {{ $level }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="file-datatable" class="table table-bordered text-nowrap key-buttons border-bottom">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0">ID</th>
                                            <th class="border-bottom-0">Câu hỏi</th>
                                            <th class="border-bottom-0">Cấp độ</th>
                                            <th class="border-bottom-0">Điểm thưởng</th>
                                            <th class="border-bottom-0">Vé quay</th>
                                            <th class="border-bottom-0">Phần thưởng</th>
                                            <th class="border-bottom-0">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($questions as $question)
                                        <tr>
                                            <td>{{ $question->id }}</td>
                                            <td>{{ Str::limit($question->question, 50) }}</td>
                                            <td>
                                                @if($question->difficulty_level == 1)
                                                    <span class="badge badge-success">Dễ</span>
                                                @elseif($question->difficulty_level == 2)
                                                    <span class="badge badge-warning">Trung bình</span>
                                                @else
                                                    <span class="badge badge-danger">Khó</span>
                                                @endif
                                            </td>
                                            <td>{{ $question->points_reward }}</td>
                                            <td>{{ $question->spin_tickets }}</td>
                                            <td>
                                                @if($question->reward)
                                                    <span class="badge badge-primary">{{ $question->reward->name }}</span>
                                                @else
                                                    <span class="badge badge-secondary">Không có</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.questions.show', $question->id) }}" class="btn ripple btn-info"><i class="fe fe-eye"></i></a>
                                                <a href="{{ route('admin.questions.edit', $question->id) }}" class="btn ripple btn-success"><i class="fe fe-edit"></i></a>
                                                <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn ripple btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?')"><i class="fe fe-trash"></i></button>
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
