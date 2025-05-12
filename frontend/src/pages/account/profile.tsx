import { useState, useEffect } from "react";
import { Button, Icon, Avatar, Radio } from "zmp-ui";
import { useNavigate } from "react-router-dom";
import { getUserInfo, getPhoneNumber, getAccessToken, authorize, getSetting } from "zmp-sdk";
import toast from "react-hot-toast";
import axios from "axios";
import React from 'react';
import { useAtomValue } from 'jotai';
import { gameHistoryState } from '@/state';

const apiClient = axios.create({
  baseURL: 'https://agrijapanvn.com.vn',
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
    email: "", // Default gender
    idByOA: "",
    followedOA: false,
    isSensitive: false
  });
  const [loading, setLoading] = useState(false);
  const [hasProvidedPermissions, setHasProvidedPermissions] = useState(false);
  const [permissionsChecked, setPermissionsChecked] = useState(false);
  const [debug, setDebug] = useState({
    tokenInfo: "",
    userInfo: "",
    errorInfo: "",
    locationStatus: "",
    locationToken: "",
    accessToken: "",
    locationResponse: ""
  });
  const [showDebug, setShowDebug] = useState(false);
  
  const gameHistory = useAtomValue(gameHistoryState);
  
  useEffect(() => {
    // Kiểm tra quyền từ SDK Zalo trước khi lấy thông tin
    checkPermissions();
    
    // Nếu đã có zaloId trong profile, lấy luôn thông tin đầy đủ từ backend
    if (profile.id) {
      console.log("Profile already has zaloId, fetching full info directly");
      fetchFullUserInfo(profile.id);
    }
  }, []);

  // Kiểm tra quyền đã được cấp
  const checkPermissions = async () => {
    try {
      // Kiểm tra quyền hiện tại
      const { authSetting } = await getSetting({});
      console.log("Auth settings:", authSetting);
      
      const isDev = !window.ZJSBridge;
      const hasUserInfoPermission = authSetting["scope.userInfo"] || isDev;
      const hasPhoneNumberPermission = authSetting["scope.userPhonenumber"] || isDev;
      
      if (hasUserInfoPermission && hasPhoneNumberPermission) {
        setHasProvidedPermissions(true);
        fetchUserInfo();
      } else {
        setHasProvidedPermissions(false);
      }
      
      setPermissionsChecked(true);
    } catch (error) {
      console.error("Lỗi khi kiểm tra quyền:", error);
      setPermissionsChecked(true);
      setHasProvidedPermissions(false);
    }
  };

  // Yêu cầu quyền từ người dùng
  const requestPermissions = async () => {
    setLoading(true);
    try {
      // Yêu cầu quyền thông tin người dùng và số điện thoại
      await authorize({
        scopes: ["scope.userInfo", "scope.userPhonenumber"],
      });
      
      // Kiểm tra lại quyền sau khi yêu cầu
      const { authSetting } = await getSetting({});
      console.log("Auth settings after authorize:", authSetting);
      
      const isDev = !window.ZJSBridge;
      const hasUserInfoPermission = authSetting["scope.userInfo"] || isDev;
      const hasPhoneNumberPermission = authSetting["scope.userPhonenumber"] || isDev;
      
      if (hasUserInfoPermission && hasPhoneNumberPermission) {
        setHasProvidedPermissions(true);
        fetchUserInfo();
        
        if (hasPhoneNumberPermission) {
          // Lấy số điện thoại nếu đã được cấp quyền
          getPhoneFromToken();
        }
        
        toast.success("Đã cấp quyền thành công");
      } else {
        toast.error("Bạn cần cấp quyền để sử dụng tính năng này");
        setHasProvidedPermissions(false);
      }
    } catch (error) {
      console.error("Lỗi khi yêu cầu quyền:", error);
      toast.error("Có lỗi xảy ra khi yêu cầu quyền");
      setHasProvidedPermissions(false);
    } finally {
      setLoading(false);
    }
  };

  const fetchUserInfo = async () => {
    try {
      // Lấy thông tin từ Zalo API
      const result = await getUserInfo({});
      console.log("User Info from Zalo:", result);
      
      const debugUserInfo = JSON.stringify(result, null, 2);
      setDebug(prev => ({...prev, userInfo: debugUserInfo}));
      
      // Biến để lưu zaloId
      let zaloId = "";
      
      // Kiểm tra cấu trúc dữ liệu Zalo trả về
      if (result && result.userInfo) {
        // Nếu userInfo là một đối tượng (như từ log của bạn)
        console.log("User info structure:", result.userInfo);
        zaloId = result.userInfo.id || "";
        
        const updatedProfile = {
          id: result.userInfo.id || "",
          name: result.userInfo.name || "",
          avatar: result.userInfo.avatar || "",
          phone: profile.phone || "",
          email: profile.email || "",
          idByOA: result.userInfo.idByOA || "", // Fixed property name
          followedOA: result.userInfo.followedOA || false,
          isSensitive: result.userInfo.isSensitive || false
        };
        
        setProfile(updatedProfile);
        
        // Lưu thông tin cơ bản người dùng vào server
        try {
          const saveResponse = await apiClient.post('/users/save', {
            zaloId: result.userInfo.id || "",
            name: result.userInfo.name || "",
            avatar: result.userInfo.avatar || "",
            idByOA: result.userInfo.idByOA || "", // Fixed property name
            followedOA: result.userInfo.followedOA || false,
            isSensitive: result.userInfo.isSensitive || false
          });
          console.log("User info saved:", saveResponse.data);
          
          // Sau khi lưu thông tin cơ bản, lấy thông tin đầy đủ từ backend
          if (zaloId) {
            fetchFullUserInfo(zaloId);
          }
          
          // Nếu chưa có số điện thoại và đã cấp quyền, thử lấy số điện thoại
          if (!profile.phone && hasProvidedPermissions) {
            console.log("No phone number yet, will try to get phone number");
            // Đánh dấu là sẽ lấy số điện thoại ngay sau khi component mount xong
            setTimeout(() => {
              getPhoneFromToken();
            }, 500);
          }
        } catch (saveError) {
          console.error("Error saving basic user info:", saveError);
          // Nếu lưu cơ bản thất bại, vẫn thử lấy thông tin đầy đủ nếu có zaloId
          if (zaloId) {
            fetchFullUserInfo(zaloId);
          }
        }
      } else if (result && result.userInfo && result.userInfo.id) {
        // Handle alternate structure - directly accessing properties from userInfo
        zaloId = result.userInfo.id || "";
        
        const updatedProfile = {
          id: result.userInfo.id || "",
          name: result.userInfo.name || "",
          avatar: result.userInfo.avatar || "",
          phone: profile.phone || "",
          email: profile.email || "",
          idByOA: result.userInfo.idByOA || "",
          followedOA: result.userInfo.followedOA || false,
          isSensitive: result.userInfo.isSensitive || false
        };
        
        setProfile(updatedProfile);
        
        // Lưu thông tin cơ bản người dùng vào server
        try {
          const saveResponse = await apiClient.post('/users/save', {
            zaloId: result.userInfo.id || "",
            name: result.userInfo.name || "",
            avatar: result.userInfo.avatar || "",
            idByOA: result.userInfo.idByOA || "",
            followedOA: result.userInfo.followedOA || false,
            isSensitive: result.userInfo.isSensitive || false
          });
          console.log("User info saved:", saveResponse.data);
          
          // Sau khi lưu thông tin cơ bản, lấy thông tin đầy đủ từ backend
          if (zaloId) {
            fetchFullUserInfo(zaloId);
          }
          
          // Nếu chưa có số điện thoại và đã cấp quyền, thử lấy số điện thoại
          if (!profile.phone && hasProvidedPermissions) {
            console.log("No phone number yet, will try to get phone number");
            // Đánh dấu là sẽ lấy số điện thoại ngay sau khi component mount xong
            setTimeout(() => {
              getPhoneFromToken();
            }, 500);
          }
        } catch (saveError) {
          console.error("Error saving basic user info:", saveError);
          // Nếu lưu cơ bản thất bại, vẫn thử lấy thông tin đầy đủ nếu có zaloId
          if (zaloId) {
            fetchFullUserInfo(zaloId);
          }
        }
      }
    } catch (error) {
      console.error("Không thể lấy thông tin người dùng:", error);
      setDebug(prev => ({...prev, errorInfo: JSON.stringify(error, null, 2)}));
    }
  };
  
  // Hàm để lấy thông tin đầy đủ của người dùng từ backend
  const fetchFullUserInfo = async (zaloId) => {
    try {
      console.log("Fetching full user info from backend for zaloId:", zaloId);
      const response = await apiClient.post('/users/get-by-zalo-id', {
        zaloId: zaloId
      });
      
      if (response.data.success && response.data.user) {
        const userData = response.data.user;
        console.log("Full user data from backend:", userData);
        
        // Cập nhật profile với thông tin đầy đủ
        setProfile(prev => ({
          ...prev,
          phone: userData.phone || prev.phone,
          email: userData.email || prev.email,
          // Có thể cập nhật thêm các trường khác nếu cần
        }));
        
        console.log("Profile updated with full user data");
      } else {
        console.warn("No full user data found from backend");
      }
    } catch (error) {
      console.error("Error fetching full user info:", error);
      // Không hiển thị thông báo lỗi vì đây là quá trình ngầm
    }
  };

  const getPhoneFromToken = async () => {
    try {
      console.log("Attempting to get phone token...");
      const result = await getPhoneNumber({});
      console.log("Phone token received:", result);
      
      console.log("Attempting to get access token...");
      const accessTokenResult = await getAccessToken();
      console.log("Access token result type:", typeof accessTokenResult);
      console.log("Access token result:", accessTokenResult);
      
      // Ensure accessToken is properly handled regardless of return type
      const accessToken = typeof accessTokenResult === 'string' 
        ? accessTokenResult 
        : (accessTokenResult as any).accessToken || '';
      
      console.log("Final access token:", accessToken);
      
      // Log để debug
      setDebug(prev => ({...prev, tokenInfo: JSON.stringify(result, null, 2)}));
      
      // Kiểm tra và đảm bảo có zaloId trước khi tiếp tục
      let currentZaloId = profile.id;
      
      // Nếu không có zaloId, thử lấy lại thông tin người dùng
      if (!currentZaloId) {
        console.log("zaloId is empty, fetching user info first");
        try {
          // Lấy thông tin người dùng trực tiếp và đợi kết quả
          const userInfoResult = await getUserInfo({});
          if (userInfoResult && userInfoResult.userInfo && userInfoResult.userInfo.id) {
            currentZaloId = userInfoResult.userInfo.id;
            console.log("Got zaloId directly:", currentZaloId);
            
            // Cập nhật profile state nhưng không đợi API lưu
            setProfile(prev => ({
              ...prev, 
              id: currentZaloId,
              name: userInfoResult.userInfo.name || prev.name,
              avatar: userInfoResult.userInfo.avatar || prev.avatar,
              idByOA: userInfoResult.userInfo.idByOA || prev.idByOA,
              followedOA: userInfoResult.userInfo.followedOA || prev.followedOA,
              isSensitive: userInfoResult.userInfo.isSensitive || prev.isSensitive
            }));
            
            // Gửi thông tin lên server trong background
            apiClient.post('/users/save', {
              zaloId: currentZaloId,
              name: userInfoResult.userInfo.name || "",
              avatar: userInfoResult.userInfo.avatar || "",
              idByOA: userInfoResult.userInfo.idByOA || "",
              followedOA: userInfoResult.userInfo.followedOA || false,
              isSensitive: userInfoResult.userInfo.isSensitive || false
            }).then(res => {
              console.log("Background save user info:", res.data);
            }).catch(err => {
              console.error("Error in background save:", err);
            });
          } else {
            toast.error("Không thể xác định ID người dùng, vui lòng thử lại");
            return;
          }
        } catch (error) {
          console.error("Error fetching user info:", error);
          toast.error("Không thể lấy thông tin người dùng, vui lòng thử lại");
          return;
        }
      }
      
      if (!currentZaloId) {
        toast.error("Không thể xác định ID người dùng sau nhiều lần thử");
        return;
      }
      
      // Chuẩn bị dữ liệu người dùng để gửi cùng với token
      const userData = {
        name: profile.name,
        avatar: profile.avatar,
        idByOA: profile.idByOA,
        followedOA: profile.followedOA,
      };
      
      console.log("Sending request with user data and zaloId:", currentZaloId);
      
      // Gửi cả token, access token và thông tin người dùng đến server trong 1 lần duy nhất
      try {
        const response = await axios({
          method: 'post',
          url: 'https://agrijapanvn.com.vn/zalo/process-phone-token',
          data: {
            zaloId: currentZaloId,
            token: result.token,
            accessToken: accessToken,
            userData: userData
          },
          headers: {
            'Content-Type': 'application/json'
          }
        });
        
        console.log("Phone process response:", response.data);
        
        if (response.data.success) {
          if (response.data.phone) {
            const phoneNumber = response.data.phone;
            
            // Cập nhật thông tin profile với số điện thoại
            setProfile(prev => ({...prev, phone: phoneNumber}));
            toast.success(response.data.message || "Đã lấy số điện thoại thành công");
            
            // Nếu có thông tin người dùng từ API, cập nhật thông tin profile
            if (response.data.user) {
              const userData = response.data.user;
              console.log("Complete user data returned:", userData);
              
              setProfile(prev => ({
                ...prev,
                email: userData.email || prev.email,
                // Có thể cập nhật thêm các trường khác nếu cần
              }));
            }
            
            // Lấy thông tin đầy đủ từ backend sau khi đã cập nhật số điện thoại
            setTimeout(() => {
              fetchFullUserInfo(currentZaloId);
            }, 1000);
          } else {
            toast.success(response.data.message || "Đã ghi nhận token");
          }
        } else {
          toast.error(response.data.message || "Có lỗi khi xử lý token");
        }
      } catch (procError) {
        console.error("Error with process route:", procError);
        setDebug(prev => ({...prev, errorInfo: JSON.stringify(procError, null, 2)}));
        toast.error("Lỗi khi gọi route xử lý token");
      }
    } catch (error) {
      console.error("Error getting phone number:", error);
      console.error("Error type:", typeof error);
      console.error("Error details:", JSON.stringify(error));
      setDebug(prev => ({...prev, errorInfo: JSON.stringify(error, null, 2)}));
      toast.error("Không thể lấy số điện thoại");
    }
  };

  const handleInputChange = (field, value) => {
    setProfile({
      ...profile,
      [field]: value
    });
  };

  const toggleDebug = () => {
    setShowDebug(!showDebug);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Update profile logic here
    try {
      // Would send updated profile to server in a real implementation
      console.log("Saving updated profile:", profile);
      
      // Gửi thông tin cập nhật lên server
      setLoading(true);
      apiClient.post('/users/update-profile', {
        zaloId: profile.id,
        email: profile.email,
        phone: profile.phone,
        // Có thể thêm các trường khác nếu cần
        // name: profile.name,
        // birthdate: profile.birthdate,
        // gender: profile.gender
      })
      .then(response => {
        console.log("Update profile response:", response.data);
        if (response.data.success) {
          toast.success("Cập nhật thông tin thành công");
          navigate(-1);
        } else {
          toast.error(response.data.message || "Cập nhật thông tin thất bại");
        }
        setLoading(false);
      })
      .catch(error => {
        console.error("Error updating profile:", error);
        toast.error("Lỗi khi cập nhật thông tin");
        setLoading(false);
      });
    } catch (error) {
      console.error("Error updating profile:", error);
      toast.error("Lỗi khi cập nhật thông tin");
      setLoading(false);
    }
  };

  // Kiểm tra nếu chưa sẵn sàng
  if (!permissionsChecked) {
    return (
      <div className="flex flex-col h-full bg-white">
        <div className="flex-1 flex flex-col items-center justify-center p-4">
          <div className="animate-spin w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full"></div>
          <p className="mt-4 text-gray-500">Đang kiểm tra quyền...</p>
        </div>
      </div>
    );
  }

  // If user hasn't provided permissions yet, show the permission request screen
  if (!hasProvidedPermissions) {
    return (
      <div className="flex flex-col h-full bg-white">
        <div className="flex-1 flex flex-col items-center justify-center p-4">
          <div className="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mb-6">
            <Icon icon="zi-user" size={40} className="text-gray-400" />
          </div>
          <h2 className="text-xl font-bold mb-8">Cấp quyền thông tin</h2>
          <p className="text-gray-500 text-center mb-8">
            Để sử dụng đầy đủ tính năng, vui lòng cấp quyền truy cập thông tin tài khoản
          </p>
          <Button 
            onClick={requestPermissions}
            loading={loading}
            size="large"
            fullWidth
          >
            Cấp quyền thông tin
          </Button>
        </div>
      </div>
    );
  }

  // Main profile edit form - similar to screenshot
  return (
    <div className="flex flex-col h-full bg-white">

      <div className="flex-1 overflow-y-auto">
        {/* Profile image */}
        <div className="flex justify-center py-6 border-b border-gray-100">
          <div className="relative">
            {profile.avatar ? (
              <Avatar src={profile.avatar} size={80} />
            ) : (
              <div className="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center">
                <Icon icon="zi-user" size={32} className="text-gray-400" />
              </div>
            )}
          </div>
        </div>

        {/* Debug section */}
        {showDebug && (
          <div className="bg-gray-100 p-4 overflow-hidden text-sm">
            <h4 className="font-semibold mb-2">Debug Info:</h4>
            <div>
              <p><strong>Token Info:</strong> {debug.tokenInfo}</p>
              <p><strong>User Info:</strong> {debug.userInfo}</p>
              <p><strong>Error Info:</strong> {debug.errorInfo}</p>
            </div>
          </div>
        )}

        {/* Form fields */}
        <div className="px-4">
          <h2 className="text-lg font-medium py-4">Thông tin cá nhân</h2>
          
          <div className="space-y-4">
            {/* Họ và tên */}
            <div>
              <label className="block text-sm text-gray-600 mb-1">Họ và tên</label>
              <input
                type="text"
                value={profile.name}
                onChange={(e) => handleInputChange('name', e.target.value)}
                className="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500"
                placeholder="Nhập họ và tên"
                disabled
                />
            </div>
            
            {/* Số điện thoại */}
            <div>
              <label className="block text-sm text-gray-600 mb-1">Số điện thoại</label>
              <div className="flex">
                <div className="bg-gray-100 border border-gray-300 rounded-l-lg p-3 text-gray-600">
                  +84
                </div>
                <input
                  type="tel"
                  value={profile.phone?.replace('+84', '') || ''}
                  onChange={(e) => handleInputChange('phone', '+84' + e.target.value)}
                  className="flex-1 border border-gray-300 rounded-r-lg p-3 focus:outline-none focus:border-blue-500"
                  placeholder="Nhập số điện thoại"
                  disabled
                />
              </div>
            </div>
            
            {/* Email */}
            <div>
              <label className="block text-sm text-gray-600 mb-1">Email</label>
              <input
                type="email"
                value={profile.email}
                onChange={(e) => handleInputChange('email', e.target.value)}
                className="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500"
                placeholder="Nhập địa chỉ email"
              />
            </div>
            
            {/* Ngày sinh */}
            {/* <div>
              <label className="block text-sm text-gray-600 mb-1">Ngày sinh</label>
              <input
                type="text"
                value={profile.birthdate}
                onChange={(e) => handleInputChange('birthdate', e.target.value)}
                className="w-full border border-gray-300 rounded-lg p-3 focus:outline-none focus:border-blue-500"
                placeholder="DD/MM/YYYY"
              />
            </div> */}
            
            {/* Giới tính */}
            {/* <div>
              <label className="block text-sm text-gray-600 mb-3">Giới tính</label>
              <div className="flex space-x-8">
                <label className="flex items-center">
                  <input
                    type="radio"
                    name="gender"
                    value="Nam"
                    checked={profile.gender === 'Nam'}
                    onChange={() => handleInputChange('gender', 'Nam')}
                    className="mr-2"
                  />
                  Nam
                </label>
                <label className="flex items-center">
                  <input
                    type="radio"
                    name="gender"
                    value="Nữ"
                    checked={profile.gender === 'Nữ'}
                    onChange={() => handleInputChange('gender', 'Nữ')}
                    className="mr-2"
                  />
                  Nữ
                </label>
              </div>
            </div> */}
          </div>
        </div>
      </div>
      
      {/* Save button */}
      <div className="p-4 border-t border-gray-100">
        <Button htmlType="submit" fullWidth onClick={handleSubmit} loading={loading}>
          Lưu
        </Button>
      </div>
    </div>
  );
} 