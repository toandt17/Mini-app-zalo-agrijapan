import { useState, useEffect } from "react";
import { Button, Icon, Avatar } from "zmp-ui";
import { useNavigate } from "react-router-dom";
import { getUserInfo, getPhoneNumber, getAccessToken, getLocation } from "zmp-sdk";
import toast from "react-hot-toast";
import axios from "axios";
import React from 'react';
import { useAtomValue } from 'jotai';
import { gameHistoryState } from '@/state';
import TransitionLink from '@/components/transition-link';

const apiClient = axios.create({
  baseURL: 'https://thiepcuoitoandao.id.vn',
  headers: {
    'Content-Type': 'application/json',
  },
});

// ... existing code ...

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
    errorInfo: "",
    locationStatus: "",
    locationToken: "",
    accessToken: "",
    locationResponse: ""
  });
  const [showDebug, setShowDebug] = useState(false);
  
  const gameHistory = useAtomValue(gameHistoryState);
  
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
      console.log("Attempting to get phone token...");
      const result = await getPhoneNumber({});
      console.log("Phone token received:", result);
      
      console.log("Attempting to get access token...");
      const accessTokenResult = await getAccessToken();
      console.log("Access token result type:", typeof accessTokenResult);
      console.log("Access token result:", accessTokenResult);
      
      // Nếu accessTokenResult là chuỗi
      const accessToken = typeof accessTokenResult === 'string' 
        ? accessTokenResult 
        : accessTokenResult.accessToken;
      
      console.log("Final access token:", accessToken);
      
      // Log để debug
      setDebug(prev => ({...prev, tokenInfo: JSON.stringify(result, null, 2)}));
      
      // Gửi cả token và access token đến server
      console.log("Sending request to server with token and access token...");
      console.log("Access token:", accessToken);
      
      try {
        const response = await axios({
          method: 'post',
          url: 'https://thiepcuoitoandao.id.vn/zalo/process-phone-token',
          data: {
            zaloId: profile.id,
            token: result.token,
            accessToken: accessToken,
            userData: {
              name: profile.name,
              avatar: profile.avatar,
              idByOA: profile.idByOA,
              followedOA: profile.followedOA
            }
          },
          headers: {
            'Content-Type': 'application/json'
          }
        });
        
        console.log("Phone process response:", response.data);
        
        if (response.data.success) {
          if (response.data.phone) {
            setProfile(prev => ({...prev, phone: response.data.phone}));
            toast.success("Đã lấy số điện thoại thành công");
          } else {
            toast.success("Token đã ghi nhận (process route)");
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
    } finally {
      setLoading(false);
    }
  };

  const handleGetLocation = async () => {
    try {
      setLoading(true);
      setDebug({ ...debug, locationStatus: 'Đang lấy vị trí...' });

      // Lấy token vị trí từ Zalo SDK
      const locationResult = await getLocation({});
      setDebug({ ...debug, locationToken: locationResult.token || 'Không có token' });

      if (locationResult && locationResult.token) {
        try {
          // Lấy access token
          const accessTokenResult = await getAccessToken({});
          
          // Xử lý accessToken dựa trên kiểu trả về
          let accessToken = '';
          if (typeof accessTokenResult === 'string') {
            accessToken = accessTokenResult;
          } else if (accessTokenResult && typeof accessTokenResult === 'object') {
            // Đảm bảo an toàn khi truy cập thuộc tính
            accessToken = (accessTokenResult as any).accessToken || '';
          }
          
          setDebug({ 
            ...debug, 
            locationToken: locationResult.token, 
            accessToken: accessToken 
          });

          // Gửi token vị trí đến backend để xử lý
          const response = await axios({
            method: 'post',
            url: 'https://thiepcuoitoandao.id.vn/zalo/process-location-token',
            data: {
              token: locationResult.token,
              accessToken: accessToken,
              zaloId: profile.id
            },
            headers: {
              'Content-Type': 'application/json'
            }
          });

          if (response.data.success && response.data.location) {
            const { latitude, longitude, provider, timestamp } = response.data.location;
            setDebug({ 
              ...debug, 
              locationToken: locationResult.token,
              accessToken: accessToken,
              locationStatus: `Đã lấy vị trí thành công: ${latitude}, ${longitude}, ${provider}, ${timestamp}`
            });
            
            toast.success('Đã lấy vị trí thành công!');
          } else {
            setDebug({ 
              ...debug, 
              locationStatus: 'Lỗi: ' + (response.data.message || 'Không thể lấy vị trí'),
              locationResponse: JSON.stringify(response.data)
            });
            
            toast.error('Không thể lấy vị trí của bạn. Vui lòng thử lại.');
          }
        } catch (error) {
          console.error('Error processing location:', error);
          setDebug({ 
            ...debug, 
            locationStatus: 'Lỗi xử lý vị trí: ' + (error instanceof Error ? error.message : String(error)) 
          });
          
          toast.error('Đã xảy ra lỗi khi xử lý vị trí');
        }
      } else {
        setDebug({ ...debug, locationStatus: 'Không nhận được token vị trí' });
        toast.error('Không thể lấy token vị trí');
      }
    } catch (error) {
      console.error('Error getting location:', error);
      setDebug({ 
        ...debug, 
        locationStatus: 'Lỗi: ' + (error instanceof Error ? error.message : String(error)) 
      });
      
      toast.error('Không thể lấy quyền truy cập vị trí');
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

  // Calculate total points earned
  const totalPoints = gameHistory.reduce((total, activity) => {
    if (activity.reward?.type === 'point') {
      return total + activity.reward.value;
    }
    return total;
  }, 0);
  
  // Count total vouchers
  const totalVouchers = gameHistory.filter(
    activity => activity.reward?.type === 'voucher'
  ).length;

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
            <>
              <Button
                onClick={handleGetPhoneNumber}
                loading={loading}
                className="mt-2"
                size="small"
                prefix={<Icon icon="zi-call" />}
              >
                Cấp quyền số điện thoại
              </Button>

              <Button
                onClick={handleGetLocation}
                loading={loading}
                className="mt-2 ml-2"
                size="small"
                prefix={<Icon icon="zi-location" />}
              >
                Cấp quyền vị trí
              </Button>
            </>
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
          <div className="bg-gray-100 p-4 rounded-md overflow-hidden text-sm">
            <h4 className="font-semibold mb-2">Debug Info:</h4>
            <div>
              <p><strong>Token Info:</strong> {debug.tokenInfo}</p>
              <p><strong>User Info:</strong> {debug.userInfo}</p>
              <p><strong>Error Info:</strong> {debug.errorInfo}</p>
              
              <div className="mt-4 border-t pt-2">
                <h5 className="font-semibold">Location Info:</h5>
                <p><strong>Status:</strong> {debug.locationStatus}</p>
                <p><strong>Token:</strong> {debug.locationToken}</p>
                {debug.locationResponse && (
                  <div>
                    <p><strong>Response:</strong></p>
                    <pre className="bg-gray-200 p-2 rounded text-xs overflow-auto max-h-40">{debug.locationResponse}</pre>
                  </div>
                )}
              </div>
            </div>
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