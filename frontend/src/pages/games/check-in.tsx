import React, { useState, useEffect } from 'react';
import { useAtom } from 'jotai';
import { checkInState, luckyWheelState, gameHistoryState } from '@/state';
import { Button, Icon, Spinner } from 'zmp-ui';
import { format } from 'date-fns';
import { checkInDaily, getCheckInHistory, getCheckInRewards, saveUser } from '@/api/gameApi';
import toast from 'react-hot-toast';
import { getUserInfo } from 'zmp-sdk';

// Định nghĩa kiểu dữ liệu phần thưởng đơn giản nếu không có trong types
interface CheckInReward {
  day?: number;
  id?: number;
  name: string;
}

// Lưu trữ ID người dùng từ database
interface UserData {
  id: number;
  zaloId: string | number;
  name?: string;
}

// Cập nhật kiểu dữ liệu checkInState để hỗ trợ checkedDaysThisWeek
interface CheckInStateType {
  lastCheckIn: Date | null;
  consecutive: number;
  total: number;
  checkedInToday: boolean;
  checkedDaysThisWeek: Set<number>;
}

// Component sẽ được cập nhật để sử dụng API thay vì dữ liệu cứng
export default function CheckInPage() {
  const [checkIn, setCheckIn] = useAtom(checkInState);
  const [luckyWheel, setLuckyWheel] = useAtom(luckyWheelState);
  const [gameHistory, setGameHistory] = useAtom(gameHistoryState);
  const [showReward, setShowReward] = useState(false);
  const [loading, setLoading] = useState(false);
  const [historyLoading, setHistoryLoading] = useState(true);
  const [rewards, setRewards] = useState<CheckInReward[]>([]);
  const [currentReward, setCurrentReward] = useState<{id: number, name: string, type: string, value: number, color: string} | null>(null);
  const [userData, setUserData] = useState<UserData | null>(null);
  const [userSaved, setUserSaved] = useState(false);
  
  // Load thông tin người dùng từ SDK Zalo
  useEffect(() => {
    async function fetchUserInfo() {
      try {
        // Check if running in dev mode (browser)
        const isDev = !window.ZJSBridge;
        
        if (isDev) {
          // Dev mode - use test ID
          console.log("Running in dev mode, using test user_id");
          setUserData({ id: 1, zaloId: "dev_user", name: "Test User" });
          setUserSaved(true); // Giả định user 1 đã được lưu trong dev mode
        } else {
          // Production - get actual Zalo user info
          const result = await getUserInfo({});
          console.log("Zalo user info:", result);
          
          if (result && result.userInfo && result.userInfo.id) {
            const zaloId = result.userInfo.id;
            
            // Lưu thông tin người dùng vào system
            const saveResult = await saveUser({
              zaloId: zaloId,
              name: result.userInfo.name || "",
              avatar: result.userInfo.avatar || "",
              idByOA: result.userInfo.idByOA || "", 
              followedOA: result.userInfo.followedOA || false,
              isSensitive: result.userInfo.isSensitive || false
            });
            
            if (saveResult.success && saveResult.user) {
              console.log("User saved successfully:", saveResult);
              // Lưu database ID từ response API
              setUserData({
                id: saveResult.user.id,
                zaloId: zaloId,
                name: saveResult.user.name
              });
              setUserSaved(true);
              
              // Hiển thị toast chào mừng nếu là user mới
              if (saveResult.is_new) {
                toast.success(`Chào mừng ${saveResult.user.name || "bạn"} đến với ứng dụng!`);
              }
            } else {
              console.error("Error saving user:", saveResult);
              toast.error("Không thể lưu thông tin người dùng");
            }
          } else {
            toast.error("Không thể lấy thông tin người dùng");
            console.error("Không thể lấy Zalo ID");
          }
        }
      } catch (error) {
        console.error("Lỗi khi lấy thông tin người dùng:", error);
        toast.error("Không thể lấy thông tin người dùng");
      }
    }
    
    fetchUserInfo();
  }, []);
  
  // Biến để kiểm tra xem người dùng đã điểm danh hôm nay chưa
  const canCheckInToday = !checkIn.checkedInToday;
  
  // Load dữ liệu điểm danh và phần thưởng khi component được mount và khi userData thay đổi
  useEffect(() => {
    if (userData && userSaved) {
      loadCheckInHistory();
      loadRewards();
    }
  }, [userData, userSaved]);
  
  // Tải lịch sử điểm danh
  const loadCheckInHistory = async () => {
    if (!userData || !userSaved) return;
    
    try {
      setHistoryLoading(true);
      // Sử dụng database ID
      const response = await getCheckInHistory(userData.id);
      
      if (response.success) {
        // Cập nhật state từ dữ liệu server
        const historyCount = response.data.history?.length || 0;
        
        // Lưu lịch sử ngày đã điểm danh trong tuần này
        const checkedDaysThisWeek = new Set();
        
        // Xử lý lịch sử điểm danh trong tuần này
        if (response.data.history && response.data.history.length > 0) {
          const today = new Date();
          const startOfWeek = new Date(today);
          // Đặt về thứ 2 của tuần này (0 = CN, 1 = T2, ...)
          startOfWeek.setDate(today.getDate() - (today.getDay() === 0 ? 6 : today.getDay() - 1));
          startOfWeek.setHours(0, 0, 0, 0);
          
          response.data.history.forEach(entry => {
            const checkinDate = new Date(entry.checkin_date);
            if (checkinDate >= startOfWeek) {
              // Lấy thứ trong tuần (1-7)
              const dayOfWeek = checkinDate.getDay() === 0 ? 7 : checkinDate.getDay();
              checkedDaysThisWeek.add(dayOfWeek);
            }
          });
          
          console.log('Ngày đã điểm danh trong tuần này:', Array.from(checkedDaysThisWeek));
        }
        
        setCheckIn({
          lastCheckIn: response.data.last_checkin ? new Date(response.data.last_checkin) : null,
          consecutive: response.data.current_streak || 0,
          total: historyCount,
          checkedInToday: response.data.checked_in_today || false,
          checkedDaysThisWeek: checkedDaysThisWeek
        });
        
        console.log('Check-in history loaded:', {
          total_checkins: historyCount,
          consecutive: response.data.current_streak,
          checkedInToday: response.data.checked_in_today,
          checkedDaysThisWeek: Array.from(checkedDaysThisWeek)
        });
      } else {
        console.error("Lỗi khi lấy lịch sử điểm danh:", response.message);
      }
    } catch (error) {
      console.error('Lỗi khi tải lịch sử điểm danh:', error);
    } finally {
      setHistoryLoading(false);
    }
  };
  
  // Tải thông tin phần thưởng
  const loadRewards = async () => {
    try {
      const response = await getCheckInRewards();
      if (response.success) {
        setRewards(response.data);
      } else {
        console.error("Lỗi khi lấy phần thưởng:", response.message);
      }
    } catch (error) {
      console.error('Lỗi khi tải thông tin phần thưởng:', error);
    }
  };
  
  // Xử lý khi người dùng bấm nút điểm danh
  const handleCheckIn = async () => {
    if (!canCheckInToday || loading || !userData || !userSaved) {
      if (!userData) {
        toast.error('Không thể xác định thông tin người dùng');
      } else if (!userSaved) {
        toast.error('Đang lưu thông tin người dùng, vui lòng đợi chút');
      }
      return;
    }
    
    try {
      setLoading(true);
      console.log("Calling checkInDaily with database ID:", userData.id);
      // Sử dụng database ID
      const response = await checkInDaily(userData.id);
      
      if (response.success) {
        // Lấy ngày trong tuần hiện tại
        const today = new Date();
        const dayOfWeek = today.getDay() === 0 ? 7 : today.getDay();
        
        // Tạo bản sao của Set hiện tại hoặc tạo mới nếu chưa có
        const updatedCheckedDays = new Set(checkIn.checkedDaysThisWeek || []);
        updatedCheckedDays.add(dayOfWeek);
        
        // Cập nhật state từ response
        setCheckIn({
          lastCheckIn: new Date(),
          consecutive: response.data.consecutive_days,
          total: checkIn.total + 1,
          checkedInToday: true,
          checkedDaysThisWeek: updatedCheckedDays
        });
        
        // Lưu thông tin phần thưởng đã nhận
        const reward = {
          id: response.data.day_in_cycle,
          name: `${response.data.points_earned > 0 ? `${response.data.points_earned} điểm` : ''}${
            response.data.points_earned > 0 && response.data.spin_tickets > 0 ? ' + ' : ''
          }${response.data.spin_tickets > 0 ? `${response.data.spin_tickets} lượt quay` : ''}`,
          type: "point",
          value: response.data.points_earned,
          color: "#4CAF50"
        };
        
        setCurrentReward(reward);
        
        // Cập nhật lịch sử hoạt động
        setGameHistory([
          {
            id: Date.now(),
            type: 'checkin',
            result: `Điểm danh ngày ${response.data.day_in_cycle}: ${reward.name}`,
            reward: reward,
            createdAt: new Date()
          },
          ...gameHistory
        ]);
        
        // Hiển thị popup phần thưởng
        setShowReward(true);
        
        // Cập nhật số lượt quay nếu nhận được từ điểm danh
        if (response.data.spin_tickets > 0 && luckyWheel) {
          const updatedWheel = {
            ...luckyWheel,
            remainingSpins: (luckyWheel.remainingSpins || 0) + response.data.spin_tickets
          };
          setLuckyWheel(updatedWheel);
        }
        
        toast.success('Điểm danh thành công!');
      } else {
        if (response.message && response.message.includes("No query results for model")) {
          // Cụ thể lỗi "User not found", thử lưu lại user
          toast.error('Tài khoản chưa được nhận diện, đang thử lại...');
          
          if (userData && userData.zaloId) {
            const retrySave = await retryUserSave();
            if (retrySave) {
              toast.success('Đã cập nhật thông tin, vui lòng thử điểm danh lại');
            } else {
              toast.error('Không thể xác thực tài khoản, vui lòng đăng nhập lại');
            }
          }
        } else {
          toast.error(response.message || 'Điểm danh thất bại, vui lòng thử lại sau.');
        }
      }
    } catch (error: any) {
      console.error('Lỗi khi điểm danh:', error);
      
      // Xử lý lỗi 500 chi tiết hơn
      if (error.response && error.response.status === 500) {
        // Kiểm tra nếu là lỗi user not found
        if (error.response.data && error.response.data.message && 
            error.response.data.message.includes("No query results for model")) {
          toast.error('Tài khoản chưa được nhận diện. Hệ thống sẽ thử đăng ký lại thông tin tài khoản.');
          
          // Thử lưu lại thông tin user
          const retrySave = await retryUserSave();
          if (retrySave) {
            toast.success('Đã cập nhật thông tin, vui lòng thử điểm danh lại');
          }
        } else {
          toast.error('Lỗi máy chủ: Có thể bạn đã điểm danh rồi hoặc server đang bảo trì');
        }
      } else {
        toast.error(error.message || 'Đã xảy ra lỗi, vui lòng thử lại sau.');
      }
    } finally {
      setLoading(false);
    }
  };
  
  // Hàm thử lưu lại thông tin user khi gặp lỗi
  const retryUserSave = async () => {
    try {
      // Lấy lại thông tin từ Zalo SDK
      const result = await getUserInfo({});
      
      if (result && result.userInfo && result.userInfo.id) {
        // Lưu thông tin người dùng vào system
        const saveResult = await saveUser({
          zaloId: result.userInfo.id,
          name: result.userInfo.name || "",
          avatar: result.userInfo.avatar || "",
          idByOA: result.userInfo.idByOA || "",
          followedOA: result.userInfo.followedOA || false,
          isSensitive: result.userInfo.isSensitive || false
        });
        
        if (saveResult.success && saveResult.user) {
          console.log("User re-saved successfully:", saveResult);
          // Cập nhật userData với database ID
          setUserData({
            id: saveResult.user.id,
            zaloId: result.userInfo.id,
            name: saveResult.user.name
          });
          setUserSaved(true);
          return true;
        }
      }
      
      return false;
    } catch (error) {
      console.error("Error retrying user save:", error);
      return false;
    }
  };
  
  // Lấy chu kỳ ngày hiện tại từ API (1-7)
  const getCurrentDayInCycle = () => {
    // Nếu có currentReward (được hiển thị khi check-in thành công) thì lấy từ đó
    if (currentReward && currentReward.id) {
      return currentReward.id;
    }
    
    // Ngược lại tính toán dựa trên ngày trong tuần (Thứ 2 = 1, CN = 7)
    const today = new Date();
    const dayOfWeek = today.getDay(); // 0 = CN, 1 = T2, ...
    return dayOfWeek === 0 ? 7 : dayOfWeek;
  };
  
  // Lấy thứ trong tuần hiện tại (0 = CN, 1-6 = T2-T7)
  const getCurrentDayOfWeek = () => {
    return new Date().getDay(); // 0 = CN, 1 = T2, 2 = T3, ...
  };
  
  // Dữ liệu phần thưởng dự phòng khi API chưa load xong
  const REWARDS = [
    { day: 1, name: "5 điểm" },
    { day: 2, name: "10 điểm" },
    { day: 3, name: "15 điểm" },
    { day: 4, name: "20 điểm" },
    { day: 5, name: "1 lượt quay" },
    { day: 6, name: "30 điểm" },
    { day: 7, name: "50 điểm + 2 lượt quay" },
  ];
  
  // Render UI
  return (
    <div className="flex flex-col p-4 h-full">
      <div className="bg-gradient-to-r from-green-100 to-teal-100 w-full rounded-xl p-4 mb-6 text-center">
        <h2 className="text-xl font-bold text-green-800 mb-2">Điểm Danh Hàng Ngày</h2>
        {historyLoading || !userSaved ? (
          <div className="flex flex-col items-center">
            <Spinner />
            <p className="text-xs text-green-600 mt-2">
              {!userSaved ? 'Đang lưu thông tin đăng nhập...' : 'Đang tải dữ liệu...'}
            </p>
          </div>
        ) : (
          <p className="text-green-600">
            Bạn đã điểm danh <span className="font-bold">{checkIn.consecutive}</span> ngày liên tiếp
          </p>
        )}
      </div>
      
      {/* Hiển thị thông tin người dùng */}
      {userData && userData.name && (
        <div className="bg-white rounded-xl p-3 shadow-md mb-3 text-center">
          <p className="text-sm text-gray-600">
            Xin chào, <span className="font-medium">{userData.name}</span>
          </p>
        </div>
      )}
      
      {/* Reward popup */}
      {showReward && currentReward && (
        <div className="fixed inset-0 flex items-center justify-center z-50 bg-black/50">
          <div className="bg-white rounded-xl p-6 w-4/5 max-w-xs text-center">
            <div className="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <Icon icon="zi-check" className="text-green-500 text-3xl" />
            </div>
            <h3 className="text-xl font-bold mb-2">Điểm danh thành công!</h3>
            <p className="mb-4">
              Bạn đã nhận được phần thưởng cho ngày {getCurrentDayInCycle()}:
              <span className="block font-bold text-green-600 mt-2">
                {currentReward.name}
              </span>
            </p>
            <Button 
              className="w-full bg-green-500"
              onClick={() => setShowReward(false)}
            >
              Đóng
            </Button>
          </div>
        </div>
      )}
      
      {/* Calendar */}
      <div className="bg-white rounded-xl p-4 shadow-md mb-6">
        <div className="grid grid-cols-7 gap-2 mb-4">
          {['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'].map((day, i) => {
            const isToday = i === getCurrentDayOfWeek();
            return (
              <div 
                key={i} 
                className={`text-center text-xs font-medium ${
                  isToday ? 'text-green-600 font-bold' : 'text-gray-500'
                }`}
              >
                {day}
                {isToday && <div className="h-1 bg-green-500 rounded-full mt-1 mx-auto w-3/4"></div>}
              </div>
            );
          })}
        </div>
        
        <div className="grid grid-cols-7 gap-2">
          {Array.from({ length: 7 }).map((_, index) => {
            const day = index + 1;
            // Lấy ngày trong tuần thực tế
            const today = new Date();
            const currentDayOfWeek = today.getDay() === 0 ? 7 : today.getDay();
            
            // Chỉ hiển thị "đã điểm danh" nếu ngày đó có trong danh sách đã điểm danh
            const isActive = checkIn.checkedDaysThisWeek?.has(day) || false;
            const isCurrent = day === currentDayOfWeek;
            
            return (
              <div 
                key={day} 
                className={`aspect-square rounded-full flex flex-col items-center justify-center p-1 ${
                  isActive 
                    ? isCurrent 
                      ? 'bg-green-500 text-white' 
                      : 'bg-green-100 text-green-800' 
                    : isCurrent
                      ? 'bg-yellow-50 border border-yellow-200 text-yellow-800'
                      : 'bg-gray-100 text-gray-400'
                }`}
              >
                <span className="text-xs font-medium">Ngày</span>
                <span className="text-sm font-bold">{day}</span>
              </div>
            );
          })}
        </div>
      </div>
      
      {/* Rewards table */}
      <div className="bg-white rounded-xl p-4 shadow-md mb-6">
        <h3 className="font-bold mb-3">Phần thưởng điểm danh:</h3>
        <div className="space-y-2">
          {(rewards.length > 0 ? rewards : REWARDS).map((reward) => {
            const day = typeof reward.day !== 'undefined' ? reward.day : (reward.id || 0);
            
            // Lấy ngày trong tuần thực tế
            const today = new Date();
            const currentDayOfWeek = today.getDay() === 0 ? 7 : today.getDay();
            
            // Chỉ hiển thị "đã điểm danh" nếu ngày đó có trong danh sách đã điểm danh
            const isCompleted = checkIn.checkedDaysThisWeek?.has(day) || false;
            const isCurrent = currentDayOfWeek === day;
            
            return (
              <div 
                key={day} 
                className={`flex items-center p-2 rounded-lg ${
                  isCompleted 
                    ? 'bg-green-100' 
                    : isCurrent 
                      ? 'bg-yellow-50 border border-yellow-200' 
                      : 'bg-gray-50'
                }`}
              >
                <div className={`w-8 h-8 rounded-full flex items-center justify-center mr-3 ${
                  isCompleted ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-600'
                }`}>
                  {isCompleted ? <Icon icon="zi-check" /> : day}
                </div>
                <div className="flex-1">
                  <div className="font-medium">Ngày {day}</div>
                  <div className={`text-sm ${isCompleted ? 'text-green-600' : 'text-gray-500'}`}>
                    {reward.name}
                  </div>
                </div>
                {isCompleted && (
                  <div className="text-xs bg-green-500 text-white px-2 py-1 rounded">
                    Đã nhận
                  </div>
                )}
              </div>
            );
          })}
        </div>
      </div>
      
      {/* Check-in button */}
      <Button 
        className={`w-full h-14 rounded-lg text-lg font-bold ${
          canCheckInToday && userData && userSaved
            ? 'bg-gradient-to-r from-green-500 to-teal-500' 
            : 'bg-gray-300'
        }`}
        disabled={!canCheckInToday || loading || !userData || !userSaved}
        onClick={handleCheckIn}
        loading={loading}
      >
        {loading ? 'Đang điểm danh...' : 
          !userSaved ? 'Đang cập nhật thông tin...' :
          canCheckInToday 
            ? 'Điểm danh ngay' 
            : `Đã điểm danh hôm nay (${format(new Date(), 'dd/MM')})`
        }
      </Button>
    </div>
  );
} 