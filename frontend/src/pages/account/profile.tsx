import { useState, useEffect } from "react";
import { Button, Icon, Avatar } from "zmp-ui";
import { useNavigate } from "react-router-dom";
import { getUserInfo, getPhoneNumber } from "zmp-sdk";
import toast from "react-hot-toast";

export default function AccountProfilePage() {
  const navigate = useNavigate();
  const [profile, setProfile] = useState({
    name: "",
    avatar: "",
    phone: ""
  });
  const [loading, setLoading] = useState(false);
  
  useEffect(() => {
    // Yêu cầu thông tin người dùng từ Zalo SDK
    fetchUserInfo();
  }, []);

  const fetchUserInfo = async () => {
    try {
      const result = await getUserInfo({});
      if (result && result.name) {
        setProfile({
          name: result.name,
          avatar: result.avatar,
          phone: profile.phone
        });
      }
    } catch (error) {
      console.error("Không thể lấy thông tin người dùng:", error);
    }
  };

  const handleGetPhoneNumber = async () => {
    setLoading(true);
    try {
      // Gọi API lấy số điện thoại - API này sẽ hiển thị màn hình xin cấp quyền mặc định của Zalo
      const result = await getPhoneNumber({
        success: (data) => {
          // Token cần được gửi đến server để đổi lấy số điện thoại thực
          console.log("Đã nhận token:", data.token);
          
          // TODO: Gửi token đến server để lấy số điện thoại thực
          // Ở đây tôi giả định số điện thoại đã được lấy thành công
          setProfile({...profile, phone: "09xxxxxxxx"});
          toast.success("Đã cấp quyền số điện thoại thành công");
        },
        fail: (error) => {
          console.error("Không thể lấy số điện thoại:", error);
          if (error.code === -201 || error.code === -202) {
            toast.error("Người dùng từ chối cấp quyền");
          } else {
            toast.error("Không thể lấy số điện thoại. Vui lòng thử lại sau");
          }
        }
      });
    } catch (error) {
      console.error("Lỗi khi gọi API:", error);
    } finally {
      setLoading(false);
    }
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
        </div>
        
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