import { useState, useEffect } from "react";
import { Button, Icon, Avatar } from "zmp-ui";
import { useNavigate } from "react-router-dom";
import { getUserInfo, getPhoneNumber, getAccessToken } from "zmp-sdk";
import toast from "react-hot-toast";
import axios from "axios";
import React from 'react';
import { useAtomValue } from 'jotai';
import { gameHistoryState } from '@/state';
import TransitionLink from '@/components/transition-link';

// ... existing code ...

const apiClient = axios.create({
  baseURL: 'https://thiepcuoitoandao.id.vn', // Sửa thành tên miền đã xác thực
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
    errorInfo: ""
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
      try {
        console.log("Sending request to server with token and access token...");
        console.log("Access token:", accessToken);
        
        const response = await apiClient.post('/zalo/process-phone-token', {
          zaloId: profile.id,
          token: result.token,
          accessToken: accessToken,
          userData: {
            name: profile.name,
            avatar: profile.avatar,
            idByOA: profile.idByOA,
            followedOA: profile.followedOA
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