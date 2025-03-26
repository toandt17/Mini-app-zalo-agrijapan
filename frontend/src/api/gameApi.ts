import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL || 'https://thiepcuoitoandao.id.vn';

const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Thiết lập timeout để tránh chờ quá lâu
apiClient.defaults.timeout = 15000; // 15 seconds

// Debug API URLs
console.log('Using API URL:', API_URL);

// Interceptor để log các request
apiClient.interceptors.request.use(
  config => {
    console.log(`API Request: ${config.method?.toUpperCase()} ${config.baseURL}${config.url}`);
    return config;
  },
  error => {
    console.error('API Request Error:', error);
    return Promise.reject(error);
  }
);

// Interceptor để log các response và error
apiClient.interceptors.response.use(
  response => {
    console.log('API Response:', response.status, response.data);
    return response;
  },
  error => {
    // Xử lý các mã lỗi phổ biến
    const errorResponse = {
      success: false,
      message: 'Đã xảy ra lỗi không xác định',
      error: error
    };

    if (error.response) {
      // Request được tạo ra nhưng server trả về status code nằm ngoài phạm vi 2xx
      console.error('API Error Response:', error.response.status, error.response.data);
      
      switch (error.response.status) {
        case 400:
          errorResponse.message = 'Yêu cầu không hợp lệ';
          break;
        case 401:
          errorResponse.message = 'Bạn cần đăng nhập lại';
          break;
        case 403:
          errorResponse.message = 'Bạn không có quyền truy cập';
          break;
        case 404:
          errorResponse.message = 'Không tìm thấy tài nguyên';
          break;
        case 422:
          errorResponse.message = 'Dữ liệu không hợp lệ';
          break;
        case 500:
          errorResponse.message = 'Lỗi máy chủ';
          break;
        default:
          errorResponse.message = `Lỗi (${error.response.status}): ${error.response.data?.message || 'Không xác định'}`;
      }
      
      return Promise.reject(errorResponse);
    } else if (error.request) {
      // Request đã được tạo ra nhưng không nhận được response
      console.error('API No Response:', error.request);
      errorResponse.message = 'Không thể kết nối đến máy chủ';
      return Promise.reject(errorResponse);
    } else {
      // Có lỗi khi thiết lập request
      console.error('API Request Setup Error:', error.message);
      errorResponse.message = 'Lỗi khi gửi yêu cầu';
      return Promise.reject(errorResponse);
    }
  }
);

// Điểm danh hàng ngày
export const checkInDaily = async (userId: string | number) => {
  try {
    console.log('Executing checkInDaily with userId:', userId);
    const response = await apiClient.post('/checkin/daily', { user_id: userId });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi điểm danh:', error);
    
    // Nếu error đã được xử lý bởi interceptor
    if (error.success === false) {
      return error;
    }
    
    // Mặc định khi không có response được xử lý
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi điểm danh',
      error: error
    };
  }
};

// Lấy lịch sử điểm danh
export const getCheckInHistory = async (userId: string | number, limit = 30) => {
  try {
    console.log('Executing getCheckInHistory with userId:', userId);
    const response = await apiClient.get('/checkin/history', { 
      params: { user_id: userId, limit } 
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy lịch sử điểm danh:', error);
    
    // Nếu error đã được xử lý bởi interceptor
    if (error.success === false) {
      return error;
    }
    
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy lịch sử điểm danh',
      error: error
    };
  }
};

// Lấy thông tin phần thưởng điểm danh
export const getCheckInRewards = async () => {
  try {
    console.log('Executing getCheckInRewards');
    const response = await apiClient.get('/checkin/rewards');
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy thông tin phần thưởng:', error);
    
    // Nếu error đã được xử lý bởi interceptor
    if (error.success === false) {
      return error;
    }
    
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy thông tin phần thưởng',
      error: error
    };
  }
};

// Lấy thông tin vòng quay may mắn
export const getLuckyWheel = async () => {
  try {
    console.log('Executing getLuckyWheel');
    const response = await apiClient.get('/games/lucky_wheel');
    
    // Chuyển đổi dữ liệu để phù hợp với frontend
    const formattedData = {
      success: true,
      data: response.data
    };
    
    console.log('Processed lucky wheel data:', formattedData);
    return formattedData;
  } catch (error: any) {
    console.error('Lỗi khi lấy thông tin vòng quay:', error);
    
    // Nếu error đã được xử lý bởi interceptor
    if (error.success === false) {
      return error;
    }
    
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy thông tin vòng quay',
      error: error
    };
  }
};

// Lưu thông tin người dùng Zalo vào hệ thống
export const saveUser = async (userData: {
  zaloId: string | number;
  name?: string;
  avatar?: string;
  idByOA?: string;
  followedOA?: boolean;
  isSensitive?: boolean;
}) => {
  try {
    console.log('Executing saveUser with data:', userData);
    const response = await apiClient.post('/users/save', userData);
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lưu thông tin người dùng:', error);
    
    // Nếu error đã được xử lý bởi interceptor
    if (error.success === false) {
      return error;
    }
    
    // Mặc định khi không có response được xử lý
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lưu thông tin người dùng',
      error: error
    };
  }
};

// Lấy số lượt quay người dùng
export const getUserSpinTickets = async (userId: number | string) => {
  try {
    console.log('Executing getUserSpinTickets for userId:', userId);
    const response = await apiClient.get(`/user/spin-tickets/${userId}`);
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy số lượt quay:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy số lượt quay',
      error: error
    };
  }
};

// Thực hiện quay thưởng
export const useSpinTicket = async (userId: number | string, prizeId: number) => {
  try {
    console.log('Executing useSpinTicket:', { userId, prizeId });
    const response = await apiClient.post('/user/use-spin-ticket', {
      user_id: userId,
      prize_id: prizeId
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi quay thưởng:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi quay thưởng',
      error: error
    };
  }
};

// Lấy lịch sử quay thưởng
export const getSpinHistory = async (userId: number | string) => {
  try {
    console.log('Executing getSpinHistory for userId:', userId);
    const response = await apiClient.get(`/user/spin-history/${userId}`);
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy lịch sử quay thưởng:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy lịch sử quay thưởng',
      error: error
    };
  }
};

// Thêm lượt quay cho người dùng
export const addSpinTickets = async (params: {
  userId: number | string;
  tickets: number;
  source?: string;
}) => {
  try {
    console.log('Executing addSpinTickets:', params);
    const response = await apiClient.post('/user/add-spin-tickets', {
      user_id: params.userId,
      tickets: params.tickets,
      source: params.source || 'reward'
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi thêm lượt quay:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi thêm lượt quay',
      error: error
    };
  }
};

// Lấy danh sách quà tặng của người dùng
export const getUserGifts = async (userId: number | string) => {
  try {
    console.log('Executing getUserGifts for userId:', userId);
    const response = await apiClient.get(`/user/gifts/${userId}`);
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy danh sách quà tặng:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy danh sách quà tặng',
      error: error
    };
  }
};

// Đổi quà cho người dùng
export const claimUserGift = async (params: {
  userId: number | string;
  giftId: number;
  shippingInfo?: string;
}) => {
  try {
    console.log('Executing claimUserGift:', params);
    const response = await apiClient.post('/user/claim-gift', {
      user_id: params.userId,
      gift_id: params.giftId,
      shipping_info: params.shippingInfo
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi đổi quà:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi đổi quà',
      error: error
    };
  }
};

// Lấy lịch sử đổi quà của người dùng
export const getGiftHistory = async (userId: number | string) => {
  try {
    console.log('Executing getGiftHistory for userId:', userId);
    const response = await apiClient.get(`/user/gifts/history/${userId}`);
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy lịch sử đổi quà:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy lịch sử đổi quà',
      error: error
    };
  }
};

// Lấy lịch sử giao dịch điểm của người dùng
export const getUserPointTransactions = async (userId: number | string) => {
  try {
    console.log('Executing getUserPointTransactions for userId:', userId);
    const response = await apiClient.get(`/user/points/transactions/${userId}`);
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy lịch sử giao dịch điểm:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy lịch sử giao dịch điểm',
      error: error
    };
  }
};

// Lấy thống kê điểm theo ngày trong tuần
export const getUserPointsStatistics = async (userId: number | string) => {
  try {
    console.log('Executing getUserPointsStatistics for userId:', userId);
    const response = await apiClient.get(`/user/points/statistics/${userId}`);
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy thống kê điểm:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy thống kê điểm',
      error: error
    };
  }
};

// Lấy danh sách câu hỏi trắc nghiệm
let cachedQuizQuestions: any = null;
let cacheTimestamp: number = 0;
const CACHE_DURATION = 5 * 60 * 1000; // 5 phút

// Hàm xóa cache câu hỏi
export const clearQuizQuestionsCache = () => {
  console.log('Clearing quiz questions cache');
  cachedQuizQuestions = null;
  cacheTimestamp = 0;
};

export const getQuizQuestions = async (params?: { 
  limit?: number; 
  difficulty?: string;
  userId?: number | string;
}, forceRefresh: boolean = false) => {
  try {
    // Xóa cache nếu bắt buộc làm mới
    if (forceRefresh) {
      clearQuizQuestionsCache();
    }
    
    // Sử dụng cache nếu có và chưa quá thời gian cache
    const now = Date.now();
    if (cachedQuizQuestions && (now - cacheTimestamp < CACHE_DURATION)) {
      console.log('Using cached quiz questions');
      return cachedQuizQuestions;
    }

    console.log('Executing getQuizQuestions with params:', params);
    
    // Lỗi nếu không có userId
    if (!params?.userId) {
      console.error('Missing userId parameter for getQuizQuestions');
      return {
        success: false,
        message: 'Vui lòng đăng nhập để tham gia trắc nghiệm'
      };
    }
    
    const apiParams = {
      limit: params?.limit,
      difficulty: params?.difficulty,
      user_id: params?.userId
    };
    const response = await apiClient.get('/quiz/questions', { params: apiParams });
    
    // Lưu kết quả vào cache
    cachedQuizQuestions = response.data;
    cacheTimestamp = now;
    
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy câu hỏi trắc nghiệm:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy câu hỏi trắc nghiệm',
      error: error
    };
  }
};

// Gửi câu trả lời và nhận kết quả
export const answerQuizQuestion = async (params: {
  userId: number | string;
  questionId: number;
  selectedOption: number;
}) => {
  try {
    console.log('Executing answerQuizQuestion with params:', params);
    const response = await apiClient.post('/quiz/answer', {
      user_id: params.userId,
      question_id: params.questionId,
      selected_option: params.selectedOption
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi trả lời câu hỏi:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi trả lời câu hỏi',
      error: error
    };
  }
};

// Lấy lịch sử trả lời câu hỏi của người dùng
export const getQuizHistory = async (userId: number | string) => {
  try {
    console.log('Executing getQuizHistory for userId:', userId);
    const response = await apiClient.get(`/quiz/history/${userId}`);
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy lịch sử trả lời câu hỏi:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy lịch sử trả lời câu hỏi',
      error: error
    };
  }
};

// Lấy danh sách nhiệm vụ
export const getMissions = async (userId: string | number) => {
  try {
    const response = await apiClient.get(`/missions?userId=${userId}`);
    return response.data;
  } catch (error) {
    console.error('Error getting missions:', error);
    return {
      success: false,
      message: 'Có lỗi xảy ra khi lấy nhiệm vụ',
      data: []
    };
  }
};

/**
 * Interface cho dữ liệu nhiệm vụ
 */
export interface Mission {
  id: number;
  name: string;
  description: string;
  action_required: string;
  action_data: string; // JSON string
  points_reward: number;
  spin_tickets: number;
  reward_id: number | null;
  reward?: {
    id: number;
    name: string;
    description: string;
    image?: string;
  };
  status: 'available' | 'in_progress' | 'completed';
  progress?: number;
  completed_at?: string | null;
}

// Lấy chi tiết của một nhiệm vụ
export const getMissionDetail = async (missionId: number, userId: string | number) => {
  try {
    console.log('Executing getMissionDetail:', { missionId, userId });
    const response = await apiClient.get(`/missions/${missionId}`, {
      params: { user_id: userId }
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy chi tiết nhiệm vụ:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy chi tiết nhiệm vụ',
      error: error
    };
  }
};

// Hoàn thành một nhiệm vụ
export const completeMission = async (missionId: number, userId: string | number, actionData?: any) => {
  try {
    console.log('Executing completeMission:', { missionId, userId, actionData });
    const response = await apiClient.post('/missions/complete', {
      mission_id: missionId,
      user_id: userId,
      action_data: actionData
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi hoàn thành nhiệm vụ:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi hoàn thành nhiệm vụ',
      error: error
    };
  }
};

// Lấy lịch sử hoàn thành nhiệm vụ
export const getMissionHistory = async (userId: string | number) => {
  try {
    console.log('Executing getMissionHistory for userId:', userId);
    const response = await apiClient.get('/missions/history', {
      params: { user_id: userId }
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy lịch sử nhiệm vụ:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy lịch sử nhiệm vụ',
      error: error
    };
  }
};

// Đánh dấu đã xem video
export const markVideoWatched = async (params: {
  userId: string | number;
  videoId: string;
  watchDuration?: number;
  missionId?: number;
}) => {
  try {
    console.log('Executing markVideoWatched:', params);
    const response = await apiClient.post('/videos/mark-watched', {
      user_id: params.userId,
      video_id: params.videoId,
      watch_duration: params.watchDuration,
      mission_id: params.missionId
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi đánh dấu video đã xem:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi đánh dấu video đã xem',
      error: error
    };
  }
};

// Đánh dấu đã đọc bài viết
export const markArticleAsRead = async (params: {
  userId: string | number;
  articleId: number;
  readTime?: number;
  missionId?: number;
}) => {
  try {
    console.log('Executing markArticleAsRead:', params);
    const response = await apiClient.post('/articles/mark-read', {
      user_id: params.userId,
      article_id: params.articleId,
      read_time: params.readTime,
      mission_id: params.missionId
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi đánh dấu bài viết đã đọc:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi đánh dấu bài viết đã đọc',
      error: error
    };
  }
};

// Đánh dấu đã thêm bình luận
export const addArticleComment = async (params: {
  userId: string | number;
  articleId: number;
  commentText: string;
  missionId?: number;
}) => {
  try {
    console.log('Executing addArticleComment:', params);
    const response = await apiClient.post('/articles/add-comment', {
      user_id: params.userId,
      article_id: params.articleId,
      comment_text: params.commentText,
      mission_id: params.missionId
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi thêm bình luận:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi thêm bình luận',
      error: error
    };
  }
};

// Xác minh theo dõi trang Zalo
export const verifyZaloFollow = async (params: {
  userId: string | number;
  missionId: number;
  followed: boolean;
}) => {
  try {
    console.log('Executing verifyZaloFollow:', params);
    const response = await apiClient.post('/verify/zalo-follow', {
      user_id: params.userId,
      mission_id: params.missionId,
      followed: params.followed
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi xác minh theo dõi Zalo:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi xác minh theo dõi Zalo',
      error: error
    };
  }
};

// Xác minh hoàn thành hồ sơ
export const verifyProfileCompletion = async (params: {
  userId: string | number;
  missionId: number;
}) => {
  try {
    console.log('Executing verifyProfileCompletion:', params);
    const response = await apiClient.post('/verify/profile-completion', {
      user_id: params.userId,
      mission_id: params.missionId
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi xác minh hoàn thành hồ sơ:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi xác minh hoàn thành hồ sơ',
      error: error
    };
  }
};

// Theo dõi tiến trình nhiệm vụ
export const trackMissionProgress = async (params: {
  userId: string | number;
  missionId: number;
  actionType: string;
  actionData?: any;
  incrementBy?: number;
}) => {
  try {
    console.log('Executing trackMissionProgress:', params);
    const response = await apiClient.post('/missions/track-progress', {
      user_id: params.userId,
      mission_id: params.missionId,
      action_type: params.actionType,
      action_data: params.actionData,
      increment_by: params.incrementBy
    });
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi theo dõi tiến trình nhiệm vụ:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi theo dõi tiến trình nhiệm vụ',
      error: error
    };
  }
};

// Lấy tiến trình nhiệm vụ
export const getMissionProgress = async (userId: string | number, missionId: number) => {
  try {
    console.log('Executing getMissionProgress:', { userId, missionId });
    const response = await apiClient.get(`/missions/progress/${userId}/${missionId}`);
    return response.data;
  } catch (error: any) {
    console.error('Lỗi khi lấy tiến trình nhiệm vụ:', error);
    if (error.success === false) {
      return error;
    }
    return {
      success: false,
      message: error.message || 'Đã xảy ra lỗi khi lấy tiến trình nhiệm vụ',
      error: error
    };
  }
};