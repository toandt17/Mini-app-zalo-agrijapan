import { useState, useEffect } from "react";
import { Button, Icon, Avatar } from "zmp-ui";
import { useNavigate } from "react-router-dom";
import { getUserInfo, getPhoneNumber } from "zmp-sdk";
import toast from "react-hot-toast";
import axios from "axios";

const apiClient = axios.create({
  baseURL: 'https://test.vieclamphuquoc.com.vn/api', // Địa chỉ Laravel local
  headers: {
    'Content-Type': 'application/json',
  },
});

export default function AccountProfilePage() {
  const navigate = useNavigate();
  const [profile, setProfile] = useState({
    id: "",
    name: "",
    avatar: "",
    phone: "",
    idByOA: "",
    followedOA: false,
    isSensitive: false
  });
  const [loading, setLoading] = useState(false);
  const [debug, setDebug] = useState({
    tokenInfo: "",
    userInfo: "",
    errorInfo: ""
  });
  const [showDebug, setShowDebug] = useState(false);
  
  useEffect(() => {
    // Yêu cầu thông tin người dùng từ Zalo SDK
    fetchUserInfo();
  }, []);

  const fetchUserInfo = async () => {
    try {
      const result = await getUserInfo({});
      console.log("User Info from Zalo:", result);
      
      const debugUserInfo = JSON.stringify(result, null, 2);
      setDebug(prev => ({...prev, userInfo: debugUserInfo}));
      
      // Kiểm tra cấu trúc dữ liệu Zalo trả về
      if (result && result.userInfo) {
        // Nếu userInfo là một đối tượng (như từ log của bạn)
        console.log("User info structure:", result.userInfo);
        
        setProfile({
          id: result.userInfo.id || "",
          name: result.userInfo.name || "",
          avatar: result.userInfo.avatar || "",
          phone: profile.phone || "",
          idByOA: result.userInfo.id_by_oa || "",
          followedOA: result.userInfo.followedOA || false,
          isSensitive: result.userInfo.isSensitive || false
        });
        
        // Lưu thông tin người dùng vào server
        try {
          const saveResponse = await apiClient.post('/users/save', {
            zaloId: result.userInfo.id || "",
            name: result.userInfo.name || "",
            avatar: result.userInfo.avatar || "",
            idByOA: result.userInfo.id_by_oa || "",
            followedOA: result.userInfo.followedOA || false,
            isSensitive: result.userInfo.isSensitive || false
          });
          console.log("User info saved:", saveResponse.data);
        } catch (saveError) {
          console.error("Error saving user info:", saveError);
        }
      } else if (result && result.id) {
        // Nếu thông tin người dùng nằm trực tiếp trong result
        setProfile({
          id: result.id || "",
          name: result.name || "",
          avatar: result.avatar || "",
          phone: profile.phone || "",
          idByOA: result.id_by_oa || "",
          followedOA: result.followedOA || false,
          isSensitive: result.isSensitive || false
        });
        
        // Lưu thông tin người dùng vào server
        try {
          const saveResponse = await apiClient.post('/users/save', {
            zaloId: result.id || "",
            name: result.name || "",
            avatar: result.avatar || "",
            idByOA: result.id_by_oa || "",
            followedOA: result.followedOA || false,
            isSensitive: result.isSensitive || false
          });
          console.log("User info saved:", saveResponse.data);
        } catch (saveError) {
          console.error("Error saving user info:", saveError);
        }
      }
    } catch (error) {
      console.error("Không thể lấy thông tin người dùng:", error);
      setDebug(prev => ({...prev, errorInfo: JSON.stringify(error, null, 2)}));
    }
  };

  const handleGetPhoneNumber = async () => {
    setLoading(true);
    try {
      // Gọi API lấy số điện thoại - API này sẽ hiển thị màn hình xin cấp quyền mặc định của Zalo
      await getPhoneNumber({
        success: async (data) => {
          // Log thông tin token để debug
          console.log("Phone token received:", data);
          
          // Lưu thông tin token vào state debug
          const tokenInfo = JSON.stringify(data, null, 2);
          setDebug(prev => ({...prev, tokenInfo: tokenInfo}));
          
          // Gửi token đến server để lấy số điện thoại thực
          try {
            // Đường dẫn API đúng theo controller Laravel
            const response = await apiClient.post('/zalo/process-phone-token', {
              zaloId: profile.id,
              token: data.token,
              userData: {
                name: profile.name,
                avatar: profile.avatar,
                idByOA: profile.idByOA,
                followedOA: profile.followedOA
              }
            });
            
            if (response.data.success) {
              // Cập nhật số điện thoại từ response của server
              setProfile({...profile, phone: response.data.phone});
              toast.success("Đã lấy số điện thoại thành công");
            } else {
              toast.error("Không thể xử lý token số điện thoại");
              console.error("Lỗi xử lý token:", response.data.message);
            }
          } catch (serverError) {
            console.error("Lỗi gửi token đến server:", serverError);
            toast.error("Lỗi kết nối đến server");
            setDebug(prev => ({...prev, errorInfo: JSON.stringify(serverError, null, 2)}));
          }
        },
        fail: (error) => {
          console.error("Không thể lấy số điện thoại:", error);
          
          // Lưu thông tin lỗi vào state debug
          setDebug(prev => ({...prev, errorInfo: JSON.stringify(error, null, 2)}));
          
          if (error.code === -201 || error.code === -202) {
            toast.error("Người dùng từ chối cấp quyền");
          } else {
            toast.error("Không thể lấy số điện thoại. Vui lòng thử lại sau");
          }
        }
      });
    } catch (error) {
      console.error("Lỗi khi gọi API:", error);
      setDebug(prev => ({...prev, errorInfo: JSON.stringify(error, null, 2)}));
    } finally {
      setLoading(false);
    }
  };

  const toggleDebug = () => {
    setShowDebug(!showDebug);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Update profile logic here
    toast.success("Cập nhật thông tin thành công");
    navigate(-1);
  };

  return (
    <div className="flex flex-col h-full bg-gray-100">
      <div className="flex-1 p-4 space-y-4">
        {/* Profile header */}
        <div className="flex flex-col items-center py-6 bg-white rounded-lg">
          {profile.avatar ? (
            <Avatar src={profile.avatar} size={80} />
          ) : (
            <div className="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center">
              <Icon icon="zi-user" size={32} className="text-gray-400" />
            </div>
          )}
          
          <h2 className="mt-4 text-xl font-bold">{profile.name || "Chưa cấp quyền"}</h2>
          
          {/* Hiển thị SĐT hoặc nút yêu cầu quyền */}
          {profile.phone ? (
            <p className="text-gray-500">{profile.phone}</p>
          ) : (
            <Button 
              onClick={handleGetPhoneNumber}
              loading={loading}
              className="mt-2"
              size="small"
              prefix={<Icon icon="zi-call" />}
            >
              Cấp quyền số điện thoại
            </Button>
          )}
          
          <Button
            onClick={toggleDebug}
            className="mt-2"
            size="small"
            variant="secondary"
          >
            {showDebug ? "Ẩn thông tin debug" : "Hiện thông tin debug"}
          </Button>
        </div>
        
        {/* Debug section */}
        {showDebug && (
          <div className="bg-gray-800 text-white rounded-lg p-4 text-xs overflow-auto max-h-96">
            <h3 className="font-bold mb-2">Debug Information:</h3>
            
            {debug.userInfo && (
              <div className="mb-4">
                <h4 className="font-bold mb-1 text-green-400">User Info:</h4>
                <pre className="whitespace-pre-wrap">{debug.userInfo}</pre>
              </div>
            )}
            
            {debug.tokenInfo && (
              <div className="mb-4">
                <h4 className="font-bold mb-1 text-blue-400">Phone Token Info:</h4>
                <pre className="whitespace-pre-wrap">{debug.tokenInfo}</pre>
              </div>
            )}
            
            {debug.errorInfo && (
              <div className="mb-4">
                <h4 className="font-bold mb-1 text-red-400">Error Info:</h4>
                <pre className="whitespace-pre-wrap">{debug.errorInfo}</pre>
              </div>
            )}
          </div>
        )}
        
        {/* Thông tin khác */}
        <div className="bg-white rounded-lg p-4 space-y-4">
          <h3 className="font-medium text-lg">Thông tin tài khoản</h3>
          <p className="text-gray-500 text-sm">
            Thông tin này được đồng bộ từ tài khoản Zalo của bạn
          </p>
          
          <div className="border-t border-gray-100 pt-3 mt-2">
            <div className="flex justify-between items-center py-2">
              <span className="text-gray-500">Tên hiển thị</span>
              <span className="font-medium">{profile.name || "Chưa cấp quyền"}</span>
            </div>
            
            <div className="flex justify-between items-center py-2">
              <span className="text-gray-500">Số điện thoại</span>
              <span className="font-medium">{profile.phone || "Chưa cấp quyền"}</span>
            </div>
          </div>
        </div>
      </div>
      <div className="p-4">
        <Button htmlType="submit" fullWidth onClick={handleSubmit}>
          Lưu thay đổi
        </Button>
      </div>
    </div>
  );
} 