@extends('admin.layouts.master')

@section('title', 'Thêm nhiệm vụ mới')

@section('content')

<div class="main-content side-content pt-0">
    <div class="main-container container-fluid">
        <div class="inner-body">

            <!-- PAGE HEADER -->
            <div class="page-header">
                <div>
                    <h2 class="main-content-title tx-24 mg-b-5">Thêm nhiệm vụ mới</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:;">Game</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.missions.index') }}">Quản lý nhiệm vụ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
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

                            <form action="{{ route('admin.missions.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Tên nhiệm vụ <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Mô tả <span class="text-danger">*</span></label>
                                    <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="action_required">Hành động yêu cầu <span class="text-danger">*</span></label>
                                    <select id="action_required" name="action_required" class="form-control" required onchange="toggleActionDataFields()">
                                        <option value="">-- Chọn hành động --</option>
                                        <option value="login_daily" {{ old('action_required') == 'login_daily' ? 'selected' : '' }}>Đăng nhập hàng ngày</option>
                                        <option value="complete_profile" {{ old('action_required') == 'complete_profile' ? 'selected' : '' }}>Hoàn thành hồ sơ</option>
                                        <option value="follow_zalo_page" {{ old('action_required') == 'follow_zalo_page' ? 'selected' : '' }}>Theo dõi fanpage Zalo</option>
                                        <option value="read_articles" {{ old('action_required') == 'read_articles' ? 'selected' : '' }}>Đọc bài viết</option>
                                        <option value="watch_youtube" {{ old('action_required') == 'watch_youtube' ? 'selected' : '' }}>Xem video YouTube</option>
                                        <option value="follow_tiktok" {{ old('action_required') == 'follow_tiktok' ? 'selected' : '' }}>Theo dõi TikTok</option>
                                        <option value="share_facebook" {{ old('action_required') == 'share_facebook' ? 'selected' : '' }}>Chia sẻ Facebook</option>
                                        <option value="share_app" {{ old('action_required') == 'share_app' ? 'selected' : '' }}>Chia sẻ ứng dụng</option>
                                        <option value="comment_posts" {{ old('action_required') == 'comment_posts' ? 'selected' : '' }}>Bình luận bài viết</option>
                                        <option value="quiz_completion" {{ old('action_required') == 'quiz_completion' ? 'selected' : '' }}>Trả lời câu hỏi</option>
                                        <option value="login_streak" {{ old('action_required') == 'login_streak' ? 'selected' : '' }}>Đăng nhập liên tiếp</option>
                                        <option value="complete_survey" {{ old('action_required') == 'complete_survey' ? 'selected' : '' }}>Hoàn thành khảo sát</option>
                                        <option value="join_livestream" {{ old('action_required') == 'join_livestream' ? 'selected' : '' }}>Tham gia livestream</option>
                                        <option value="tiktok_review" {{ old('action_required') == 'tiktok_review' ? 'selected' : '' }}>Đăng review TikTok</option>
                                        <option value="submit_article" {{ old('action_required') == 'submit_article' ? 'selected' : '' }}>Đóng góp bài viết</option>
                                        <option value="refer_users" {{ old('action_required') == 'refer_users' ? 'selected' : '' }}>Giới thiệu người dùng</option>
                                        <option value="reach_points" {{ old('action_required') == 'reach_points' ? 'selected' : '' }}>Đạt điểm tích lũy</option>
                                    </select>
                                </div>

                                <!-- Dynamic Action Data Fields -->
                                <div id="actionDataContainer" class="action-data-fields mb-4">
                                    <!-- URL Field -->
                                    <div id="url_field" class="form-group d-none">
                                        <label for="action_url">Đường dẫn URL <span class="text-danger">*</span></label>
                                        <input type="url" id="action_url" name="action_data[url]" class="form-control" value="{{ old('action_data.url') }}" placeholder="https://example.com">
                                        <small class="text-muted">Nhập đường dẫn đầy đủ (bao gồm https://)</small>
                                    </div>

                                    <!-- Article IDs Field -->
                                    <div id="article_ids_field" class="form-group d-none">
                                        <label for="article_ids">Chọn bài viết</label>
                                        <select id="article_ids" name="action_data[article_ids][]" class="form-control" multiple>
                                            @foreach($articles ?? [] as $article)
                                                <option value="{{ $article->id }}">{{ $article->title }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Bỏ trống nếu áp dụng với tất cả bài viết</small>
                                    </div>

                                    <!-- Required Count Field -->
                                    <div id="required_count_field" class="form-group d-none">
                                        <label for="required_count">Số lượng yêu cầu</label>
                                        <input type="number" id="required_count" name="action_data[required_count]" class="form-control" value="{{ old('action_data.required_count', 1) }}" min="1">
                                        <small class="text-muted">Số lượng lần hoàn thành để hoàn tất nhiệm vụ</small>
                                    </div>

                                    <!-- Watch Duration Field -->
                                    <div id="watch_duration_field" class="form-group d-none">
                                        <label for="watch_duration">Thời gian xem tối thiểu (giây)</label>
                                        <input type="number" id="watch_duration" name="action_data[watch_duration]" class="form-control" value="{{ old('action_data.watch_duration', 60) }}" min="1">
                                    </div>

                                    <!-- Required Points Field -->
                                    <div id="required_points_field" class="form-group d-none">
                                        <label for="required_points">Điểm yêu cầu</label>
                                        <input type="number" id="required_points" name="action_data[required_points]" class="form-control" value="{{ old('action_data.required_points', 100) }}" min="1">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="points_reward">Điểm thưởng <span class="text-danger">*</span></label>
                                            <input type="number" id="points_reward" name="points_reward" class="form-control" value="{{ old('points_reward', 0) }}" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="spin_tickets">Vé quay <span class="text-danger">*</span></label>
                                            <input type="number" id="spin_tickets" name="spin_tickets" class="form-control" value="{{ old('spin_tickets', 0) }}" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="reward_id">Phần thưởng</label>
                                            <select id="reward_id" name="reward_id" class="form-control">
                                                <option value="">-- Không có phần thưởng --</option>
                                                @foreach($rewards as $reward)
                                                    <option value="{{ $reward->id }}" {{ old('reward_id') == $reward->id ? 'selected' : '' }}>
                                                        {{ $reward->name }} (Còn {{ $reward->quantity }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <button type="submit" class="btn btn-primary">Lưu nhiệm vụ</button>
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

<script>
function toggleActionDataFields() {
    // Lấy loại hành động được chọn
    const actionType = document.getElementById('action_required').value;

    // Ẩn tất cả các trường
    const allFields = ['url_field', 'article_ids_field', 'required_count_field', 'watch_duration_field', 'required_points_field'];
    allFields.forEach(field => {
        if (document.getElementById(field)) {
            document.getElementById(field).classList.add('d-none');
        } else {
            console.error("Element not found:", field);
        }
    });

    // Hiển thị các trường phù hợp dựa trên loại hành động
    switch (actionType) {
        case 'watch_youtube':
            document.getElementById('url_field').classList.remove('d-none');
            document.getElementById('required_count_field').classList.remove('d-none');
            document.getElementById('watch_duration_field').classList.remove('d-none');
            break;

        case 'read_articles':
            document.getElementById('article_ids_field').classList.remove('d-none');
            document.getElementById('required_count_field').classList.remove('d-none');
            break;

        case 'comment_posts':
            document.getElementById('article_ids_field').classList.remove('d-none');
            document.getElementById('required_count_field').classList.remove('d-none');
            break;

        case 'follow_zalo_page':
        case 'follow_tiktok':
        case 'share_facebook':
        case 'join_livestream':
        case 'tiktok_review':
            document.getElementById('url_field').classList.remove('d-none');
            break;

        case 'login_streak':
        case 'refer_users':
            document.getElementById('required_count_field').classList.remove('d-none');
            break;

        case 'reach_points':
            document.getElementById('required_points_field').classList.remove('d-none');
            break;
    }
}

// Chạy hàm khi trang được tải và khi dropdown thay đổi
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM content loaded, running toggleActionDataFields");
    toggleActionDataFields();

    // Thêm sự kiện để kiểm tra khi DOM thay đổi
    setTimeout(function() {
        toggleActionDataFields();
        console.log("Running toggleActionDataFields after timeout");
    }, 500);
});
</script>

@endsection

