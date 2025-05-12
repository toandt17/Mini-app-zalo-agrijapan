import React, { useState, useEffect } from "react";
import { Button, Spinner } from "zmp-ui";
import { getUserGifts, claimUserGift, saveUser } from "@/api/gameApi";
import { toast } from "react-hot-toast";
import { getUserInfo, openChat } from "zmp-sdk";

// Interface for gift items
interface Gift {
  id: number;
  gift_id: number;
  gift_name: string;
  description: string;
  image: string;
  received_time: string;
  has_reward: boolean;
  category: number; // 0: Điểm, 1: Sản phẩm, 2: Mã giảm, 3: Chúc mừng may mắn
}

// Interface for user data
interface UserData {
  id: number;
  zaloId: string | number;
  name?: string;
}

const UserGiftsPage: React.FC = () => {
  const [gifts, setGifts] = useState<Gift[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [claimingGift, setClaimingGift] = useState<number | null>(null);
  const [userData, setUserData] = useState<UserData | null>(null);
  const [userSaved, setUserSaved] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [shippingInfo, setShippingInfo] = useState<string>("");
  const [claimModalOpen, setClaimModalOpen] = useState<boolean>(false);
  const [selectedGift, setSelectedGift] = useState<Gift | null>(null);

  const ZALO_OA_ID = "4595954910489503839";
  const BASE_URL = "https://agrijapanvn.com.vn/storage";

  // Hàm xử lý URL hình ảnh
  const getImageUrl = (imagePath: string) => {
    if (!imagePath) return '';
    
    // Nếu đã là URL đầy đủ (bắt đầu với http hoặc https)
    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
      return imagePath;
    }
    
    // Nếu bắt đầu với dấu /, bỏ dấu / để tránh lặp
    if (imagePath.startsWith('/')) {
      return `${BASE_URL}${imagePath}`;
    }
    
    // Trường hợp còn lại, thêm / vào đường dẫn
    return `${BASE_URL}/${imagePath}`;
  };

  // Lấy thông tin người dùng từ Zalo
  useEffect(() => {
    async function fetchUserInfo() {
      try {
        // Check if running in dev mode (browser)
        const isDev = !window.ZJSBridge;
        
        if (isDev) {
          // Dev mode - use test ID
          console.log("Running in dev mode, using test user_id");
          setUserData({ id: 1, zaloId: "dev_user", name: "Test User" });
          setUserSaved(true);
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
            } else {
              setError("Không thể lưu thông tin người dùng");
            }
          } else {
            setError("Không thể lấy thông tin người dùng");
          }
        }
      } catch (error) {
        console.error("Lỗi khi lấy thông tin người dùng:", error);
        setError("Không thể lấy thông tin người dùng");
      }
    }
    
    fetchUserInfo();
  }, []);

  // Lấy danh sách quà tặng khi user đã sẵn sàng
  useEffect(() => {
    if (userData && userSaved) {
      loadUserGifts();
    }
  }, [userData, userSaved]);

  // Hàm tải danh sách quà tặng
  const loadUserGifts = async () => {
    if (!userData) return;
    
    try {
      setLoading(true);
      const response = await getUserGifts(userData.id);
      
      if (response.success && response.data.gifts) {
        setGifts(response.data.gifts);
      } else {
        console.error("Lỗi khi lấy danh sách quà tặng:", response);
        toast.error(response.message || "Không thể lấy danh sách quà tặng");
      }
    } catch (error) {
      console.error("Lỗi khi lấy danh sách quà tặng:", error);
      toast.error("Đã xảy ra lỗi khi lấy danh sách quà tặng");
    } finally {
      setLoading(false);
    }
  };

  // Hàm mở modal đổi quà
  const openClaimModal = (gift: Gift) => {
    setSelectedGift(gift);
    setShippingInfo("");
    setClaimModalOpen(true);
  };

  // Hàm đổi quà
  const handleClaimGift = async () => {
    if (!userData || !selectedGift) return;
    
    try {
      setClaimingGift(selectedGift.gift_id);
      
      const response = await claimUserGift({
        userId: userData.id,
        giftId: selectedGift.gift_id,
        shippingInfo: shippingInfo
      });
      
      if (response.success) {
        toast.success("Đổi quà thành công!");
        setClaimModalOpen(false);
        // Reload danh sách quà tặng
        loadUserGifts();
      } else {
        toast.error(response.message || "Không thể đổi quà");
      }
    } catch (error) {
      console.error("Lỗi khi đổi quà:", error);
      toast.error("Đã xảy ra lỗi khi đổi quà");
    } finally {
      setClaimingGift(null);
    }
  };

  // Hàm mở Zalo OA để liên hệ
  const handleContactOA = async () => {
    try {
      if (!window.ZJSBridge) {
        // Nếu đang chạy trên trình duyệt, mở link Zalo OA
        window.open(`https://zalo.me/${ZALO_OA_ID}`, '_blank');
        return;
      }

      // Mở chat với OA
      await openChat({
        type: 'oa',
        id: ZALO_OA_ID,
        message: 'Xin chào, tôi muốn nhận quà từ vòng quay may mắn!'
      });
    } catch (error) {
      console.error("Lỗi khi mở chat Zalo:", error);
      // Fallback mở link Zalo
      window.open(`https://zalo.me/${ZALO_OA_ID}`, '_blank');
    }
  };

  // Format date time
  const formatDateTime = (dateTimeStr: string) => {
    if (!dateTimeStr) return "";
    
    const date = new Date(dateTimeStr);
    return new Intl.DateTimeFormat('vi-VN', {
      year: 'numeric',
      month: '2-digit',
      day: '2-digit',
      hour: '2-digit',
      minute: '2-digit'
    }).format(date);
  };

  // Kiểm tra xem có nút liên hệ không (sản phẩm hoặc mã giảm giá)
  const canContactForGift = (gift: Gift) => {
    return gift.category === 1 || gift.category === 2; // 1: Sản phẩm, 2: Mã giảm
  };

  // Render loading state
  if (loading) {
    return (
      <div className="flex flex-col items-center justify-center p-4 h-full">
        <Spinner />
        <p className="mt-4 text-gray-600">
          {!userSaved ? 'Đang xác thực thông tin người dùng...' : 'Đang tải danh sách quà tặng...'}
        </p>
      </div>
    );
  }

  // Render error state
  if (error) {
    return (
      <div className="flex flex-col items-center justify-center p-4 h-full">
        <div className="text-red-500 mb-4">{error}</div>
        <Button onClick={() => window.location.reload()}>Thử lại</Button>
      </div>
    );
  }

  return (
    <div className="p-4">
      <div className="bg-white rounded-xl shadow p-4 mb-4">
        <h2 className="text-lg font-bold text-center mb-2">Quà tặng của tôi</h2>
        <p className="text-gray-600 text-sm text-center">
          Danh sách quà tặng bạn đã nhận được từ các hoạt động
        </p>
      </div>

      {gifts.length === 0 ? (
        <div className="bg-white rounded-xl shadow p-6 text-center">
          <div className="text-5xl mb-4">🎁</div>
          <h3 className="text-lg font-medium mb-2">Chưa có quà tặng nào</h3>
          <p className="text-gray-600 text-sm mb-4">
            Tham gia các hoạt động và quay thưởng để nhận quà
          </p>
          <Button 
            className="mx-auto bg-green-600 text-white"
            onClick={() => window.location.href = '/games/lucky-wheel'}
          >
            Quay thưởng ngay
          </Button>
        </div>
      ) : (
        <div className="space-y-4">
          {gifts.map(gift => (
            <div 
              key={gift.id} 
              className="bg-white rounded-xl shadow p-4 flex space-x-3"
            >
              <div className="w-20 h-20 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                {gift.image ? (
                  <img 
                    src={getImageUrl(gift.image)} 
                    alt={gift.gift_name} 
                    className="w-full h-full object-cover"
                  />
                ) : (
                  <div className="text-3xl">🎁</div>
                )}
              </div>
              
              <div className="flex-1">
                <h3 className="font-medium text-base">{gift.gift_name}</h3>
                {gift.description && (
                  <p className="text-gray-600 text-sm mt-1">{gift.description}</p>
                )}
                <div className="text-xs text-gray-500 mt-1">
                  Nhận lúc: {formatDateTime(gift.received_time)}
                </div>
                
                <div className="mt-2 flex space-x-2">
                  {canContactForGift(gift) && (
                    <Button 
                      size="small"
                      className="bg-blue-500 text-white"
                      onClick={handleContactOA}
                    >
                      Liên hệ đổi quà
                    </Button>
                  )}
                </div>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Modal đổi quà */}
      {claimModalOpen && selectedGift && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
          <div className="bg-white rounded-xl p-4 w-full max-w-md">
            <h3 className="text-lg font-bold mb-4 text-center">Đổi quà</h3>
            
            <div className="mb-4 text-center">
              <div className="text-5xl mb-2">🎁</div>
              <h4 className="font-medium">{selectedGift.gift_name}</h4>
            </div>
            
            <div className="mb-4">
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Thông tin nhận quà
              </label>
              <textarea
                className="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                rows={3}
                placeholder="Địa chỉ, số điện thoại, tên người nhận..."
                value={shippingInfo}
                onChange={(e) => setShippingInfo(e.target.value)}
              />
            </div>
            
            <div className="flex space-x-2">
              <Button 
                className="flex-1 bg-gray-200"
                onClick={() => setClaimModalOpen(false)}
                disabled={claimingGift !== null}
              >
                Hủy
              </Button>
              <Button 
                className="flex-1 bg-green-600 text-white"
                onClick={handleClaimGift}
                disabled={claimingGift !== null || !shippingInfo.trim()}
              >
                {claimingGift !== null ? (
                  <span className="flex items-center justify-center">
                    <Spinner />
                    <span className="ml-1">Đang xử lý...</span>
                  </span>
                ) : "Xác nhận"}
              </Button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default UserGiftsPage;
