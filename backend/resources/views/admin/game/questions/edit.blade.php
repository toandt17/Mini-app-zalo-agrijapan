@extends('admin.layouts.master')

@section('title', 'Chỉnh sửa câu hỏi')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Chỉnh sửa câu hỏi</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Quản lý câu hỏi</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
                    </ol>
                </div>
                <div class="d-flex">
                    <div class="justify-content-center">
                        <a href="{{ route('admin.questions.index') }}">
                            <button class="btn btn-secondary" type="button">Quay lại</button>
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
                                <h6 class="main-content-label mb-3">Thông tin câu hỏi</h6>
                            </div>

                            <form action="{{ route('admin.questions.update', $question->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="question">Câu hỏi <span class="text-danger">*</span></label>
                                    <textarea name="question" id="question" class="form-control @error('question') is-invalid @enderror" rows="3" required>{{ old('question', $question->question) }}</textarea>
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="option_a">Lựa chọn A <span class="text-danger">*</span></label>
                                            <input type="text" name="option_a" id="option_a" class="form-control @error('option_a') is-invalid @enderror" value="{{ old('option_a', $question->option_a) }}" required>
                                            @error('option_a')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="option_b">Lựa chọn B <span class="text-danger">*</span></label>
                                            <input type="text" name="option_b" id="option_b" class="form-control @error('option_b') is-invalid @enderror" value="{{ old('option_b', $question->option_b) }}" required>
                                            @error('option_b')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="option_c">Lựa chọn C <span class="text-danger">*</span></label>
                                            <input type="text" name="option_c" id="option_c" class="form-control @error('option_c') is-invalid @enderror" value="{{ old('option_c', $question->option_c) }}" required>
                                            @error('option_c')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="option_d">Lựa chọn D <span class="text-danger">*</span></label>
                                            <input type="text" name="option_d" id="option_d" class="form-control @error('option_d') is-invalid @enderror" value="{{ old('option_d', $question->option_d) }}" required>
                                            @error('option_d')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="correct_answer">Đáp án đúng <span class="text-danger">*</span></label>
                                            <select name="correct_answer" id="correct_answer" class="form-control @error('correct_answer') is-invalid @enderror" required>
                                                <option value="">Chọn đáp án đúng</option>
                                                <option value="a" {{ old('correct_answer', $question->correct_answer) == 'a' ? 'selected' : '' }}>A</option>
                                                <option value="b" {{ old('correct_answer', $question->correct_answer) == 'b' ? 'selected' : '' }}>B</option>
                                                <option value="c" {{ old('correct_answer', $question->correct_answer) == 'c' ? 'selected' : '' }}>C</option>
                                                <option value="d" {{ old('correct_answer', $question->correct_answer) == 'd' ? 'selected' : '' }}>D</option>
                                            </select>
                                            @error('correct_answer')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="difficulty_level">Cấp độ khó <span class="text-danger">*</span></label>
                                            <select name="difficulty_level" id="difficulty_level" class="form-control @error('difficulty_level') is-invalid @enderror" required>
                                                <option value="">Chọn cấp độ khó</option>
                                                @foreach($difficultyLevels as $key => $level)
                                                    <option value="{{ $key }}" {{ old('difficulty_level', $question->difficulty_level) == $key ? 'selected' : '' }}>
                                                        {{ $level }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('difficulty_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="points_reward">Điểm thưởng <span class="text-danger">*</span></label>
                                            <input type="number" name="points_reward" id="points_reward" class="form-control @error('points_reward') is-invalid @enderror" value="{{ old('points_reward', $question->points_reward) }}" min="0" required>
                                            @error('points_reward')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="spin_tickets">Vé quay <span class="text-danger">*</span></label>
                                            <input type="number" name="spin_tickets" id="spin_tickets" class="form-control @error('spin_tickets') is-invalid @enderror" value="{{ old('spin_tickets', $question->spin_tickets) }}" min="0" required>
                                            @error('spin_tickets')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reward_id">Phần thưởng</label>
                                            <select name="reward_id" id="reward_id" class="form-control @error('reward_id') is-invalid @enderror">
                                                <option value="">Không có phần thưởng</option>
                                                @foreach($rewards as $reward)
                                                    <option value="{{ $reward->id }}" {{ old('reward_id', $question->reward_id) == $reward->id ? 'selected' : '' }}>
                                                        {{ $reward->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('reward_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn ripple btn-primary">
                                        <i class="fe fe-save"></i> Cập nhật câu hỏi
                                    </button>
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
