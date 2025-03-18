@extends('admin.layouts.master')

@section('title', 'Chỉnh sửa nhiệm vụ')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Chỉnh sửa nhiệm vụ</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.missions.index') }}">Quản lý nhiệm vụ</a></li>
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
                                <h6 class="main-content-label mb-1">Thông tin nhiệm vụ</h6>
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

                            <form action="{{ route('admin.missions.update', $mission->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="name">Tên nhiệm vụ <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $mission->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả <span class="text-danger">*</span></label>
                                    <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $mission->description) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="action_required">Hành động yêu cầu <span class="text-danger">*</span></label>
                                    <select id="action_required" name="action_required" class="form-control" required>
                                        <option value="">-- Chọn hành động --</option>
                                        <option value="login_daily" {{ old('action_required', $mission->action_required) == 'login_daily' ? 'selected' : '' }}>Đăng nhập hàng ngày</option>
                                        <option value="complete_profile" {{ old('action_required', $mission->action_required) == 'complete_profile' ? 'selected' : '' }}>Hoàn thành hồ sơ</option>
                                        <option value="follow_zalo_page" {{ old('action_required', $mission->action_required) == 'follow_zalo_page' ? 'selected' : '' }}>Theo dõi fanpage Zalo</option>
                                        <option value="read_articles" {{ old('action_required', $mission->action_required) == 'read_articles' ? 'selected' : '' }}>Đọc bài viết</option>
                                        <option value="watch_youtube" {{ old('action_required', $mission->action_required) == 'watch_youtube' ? 'selected' : '' }}>Xem video YouTube</option>
                                        <option value="follow_tiktok" {{ old('action_required', $mission->action_required) == 'follow_tiktok' ? 'selected' : '' }}>Theo dõi TikTok</option>
                                        <option value="share_facebook" {{ old('action_required', $mission->action_required) == 'share_facebook' ? 'selected' : '' }}>Chia sẻ Facebook</option>
                                        <option value="share_app" {{ old('action_required', $mission->action_required) == 'share_app' ? 'selected' : '' }}>Chia sẻ ứng dụng</option>
                                        <option value="comment_posts" {{ old('action_required', $mission->action_required) == 'comment_posts' ? 'selected' : '' }}>Bình luận bài viết</option>
                                        <option value="quiz_completion" {{ old('action_required', $mission->action_required) == 'quiz_completion' ? 'selected' : '' }}>Trả lời câu hỏi</option>
                                        <option value="login_streak" {{ old('action_required', $mission->action_required) == 'login_streak' ? 'selected' : '' }}>Đăng nhập liên tiếp</option>
                                        <option value="complete_survey" {{ old('action_required', $mission->action_required) == 'complete_survey' ? 'selected' : '' }}>Hoàn thành khảo sát</option>
                                        <option value="join_livestream" {{ old('action_required', $mission->action_required) == 'join_livestream' ? 'selected' : '' }}>Tham gia livestream</option>
                                        <option value="tiktok_review" {{ old('action_required', $mission->action_required) == 'tiktok_review' ? 'selected' : '' }}>Đăng review TikTok</option>
                                        <option value="submit_article" {{ old('action_required', $mission->action_required) == 'submit_article' ? 'selected' : '' }}>Đóng góp bài viết</option>
                                        <option value="refer_users" {{ old('action_required', $mission->action_required) == 'refer_users' ? 'selected' : '' }}>Giới thiệu người dùng</option>
                                        <option value="reach_points" {{ old('action_required', $mission->action_required) == 'reach_points' ? 'selected' : '' }}>Đạt điểm tích lũy</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="points_reward">Điểm thưởng <span class="text-danger">*</span></label>
                                            <input type="number" id="points_reward" name="points_reward" class="form-control" value="{{ old('points_reward', $mission->points_reward) }}" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="spin_tickets">Vé quay <span class="text-danger">*</span></label>
                                            <input type="number" id="spin_tickets" name="spin_tickets" class="form-control" value="{{ old('spin_tickets', $mission->spin_tickets) }}" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reward_id">Phần thưởng</label>
                                            <select id="reward_id" name="reward_id" class="form-control">
                                                <option value="">-- Không có phần thưởng --</option>
                                                @foreach($rewards as $reward)
                                                    <option value="{{ $reward->id }}" {{ old('reward_id', $mission->reward_id) == $reward->id ? 'selected' : '' }}>
                                                        {{ $reward->name }} (Còn {{ $reward->quantity }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    <a href="{{ route('admin.missions.index') }}" class="btn btn-secondary">Hủy</a>
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
