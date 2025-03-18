import React, { useState, useEffect } from 'react';
import { Page, Box, Text, Button, useSnackbar } from 'zmp-ui';
import { useParams, useNavigate, useLocation } from 'react-router-dom';
import axios from 'axios';
import { Agent } from '@/types';
import { MapPin, Phone, Mail, ArrowLeft, AlertCircle, Store, Clock, RefreshCcw, Check, Calendar, QrCode } from 'lucide-react';
import { getUserInfo, getPhoneNumber, getAccessToken, getLocation } from "zmp-sdk";
import { Icon } from 'zmp-ui';

const apiClient = axios.create({
  baseURL: 'https://thiepcuoitoandao.id.vn',
  headers: {
    'Content-Type': 'application/json',
  },
});

const AgentDetailPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const { openSnackbar } = useSnackbar();
  const location = useLocation();
  const [agent, setAgent] = useState<any>(null);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [userPhone, setUserPhone] = useState<string | null>(null);
  const [userLocation, setUserLocation] = useState<{latitude: number, longitude: number, provider: string, timestamp: string} | null>(null);
  const [permissionsChecked, setPermissionsChecked] = useState<boolean>(false);
  const [phonePermissionGranted, setPhonePermissionGranted] = useState<boolean>(false);
  const [locationPermissionGranted, setLocationPermissionGranted] = useState<boolean>(false);
  const [isFromQrCode, setIsFromQrCode] = useState<boolean>(false);
  const [qrCreatedTime, setQrCreatedTime] = useState<string | null>(null);

  // Kiểm tra nếu truy cập từ mã QR (có tham số created)
  useEffect(() => {
    const queryParams = new URLSearchParams(location.search);
    const createdParam = queryParams.get('created');
    if (createdParam) {
      setIsFromQrCode(true);
      console.log("Accessing from QR code with token:", createdParam);
    }
  }, [location]);

  // Kiểm tra quyền truy cập khi component được mount
  useEffect(() => {
    console.log("Detail page initialized with ID:", id);
    if (id) {
      // Kiểm tra quyền truy cập trước
      checkPermissions();
    }
  }, [id]);

  // Kiểm tra quyền truy cập số điện thoại và vị trí
  const checkPermissions = async () => {
    console.log("Checking permissions...");
    setLoading(true);
    try {
      // Kiểm tra quyền truy cập số điện thoại
      const phoneGranted = await checkPhonePermission();
      setPhonePermissionGranted(phoneGranted);
      
      // Kiểm tra quyền truy cập vị trí
      const locationGranted = await checkLocationPermission();
      setLocationPermissionGranted(locationGranted);
      
      // Đánh dấu là đã kiểm tra xong
      setPermissionsChecked(true);
      
      // Tải thông tin đại lý sau khi kiểm tra quyền
      fetchAgentDetail();
    } catch (error) {
      console.error('Error checking permissions:', error);
      setError('Vui lòng cấp quyền truy cập số điện thoại và vị trí để xem đầy đủ thông tin đại lý.');
    } finally {
      setLoading(false);
    }
  };

  // Kiểm tra quyền truy cập số điện thoại
  const checkPhonePermission = async () => {
    try {
      const userInfo = await getUserInfo({});
      console.log('User info:', userInfo);

      // Nếu đã có số điện thoại, không cần xin lại
      if (userPhone) {
        setPhonePermissionGranted(true);
        return true;
      }

      // Lấy token để lấy số điện thoại
      const phoneResult = await getPhoneNumber({});
      console.log('Phone result:', phoneResult);

      if (phoneResult && phoneResult.token) {
        try {
          // Lấy access token
          const accessTokenResult = await getAccessToken({});
          console.log('Access token result:', accessTokenResult);
          
          // Xử lý accessToken dựa trên kiểu trả về
          let accessToken = '';
          if (typeof accessTokenResult === 'string') {
            accessToken = accessTokenResult;
          } else if (accessTokenResult && typeof accessTokenResult === 'object') {
            // Đảm bảo an toàn khi truy cập thuộc tính
            accessToken = (accessTokenResult as any).accessToken || '';
          }
          
          // Gửi token số điện thoại để xử lý
          const response = await apiClient.post('/zalo/process-phone-token', {
            token: phoneResult.token,
            accessToken: accessToken,
            zaloId: userInfo.userInfo?.id || ''
          });
          
          if (response.data.success && response.data.phone) {
            setUserPhone(response.data.phone);
            setPhonePermissionGranted(true);
            return true;
          } else {
            console.error('Error processing phone token:', response.data);
            openSnackbar({
              text: 'Không thể lấy số điện thoại. Vui lòng thử lại.',
              type: 'error',
              duration: 3000
            });
            return false;
          }
        } catch (error) {
          console.error('Error processing phone token:', error);
          openSnackbar({
            text: 'Đã xảy ra lỗi khi xử lý số điện thoại',
            type: 'error',
            duration: 3000
          });
          return false;
        }
      } else {
        console.error('No phone token received');
        openSnackbar({
          text: 'Không thể lấy token số điện thoại',
          type: 'error',
          duration: 3000
        });
        return false;
      }
    } catch (error) {
      console.error('Error getting phone permission:', error);
      openSnackbar({
        text: 'Không thể lấy quyền truy cập số điện thoại',
        type: 'error',
        duration: 3000
      });
      return false;
    }
  };

  // Kiểm tra quyền truy cập vị trí
  const checkLocationPermission = async () => {
    try {
      // Kiểm tra quyền vị trí
      const locationResult = await getLocation({});
      console.log("Location result:", locationResult);
      
      if (locationResult.token) {
        console.log("Got location token:", locationResult.token);
        
        try {
          // Lấy access token
          const accessTokenResult = await getAccessToken({});
          console.log("Access token result:", accessTokenResult);
          
          // Xử lý accessToken dựa trên kiểu trả về
          let accessToken = '';
          if (typeof accessTokenResult === 'string') {
            accessToken = accessTokenResult;
          } else if (accessTokenResult && typeof accessTokenResult === 'object') {
            // Đảm bảo an toàn khi truy cập thuộc tính
            accessToken = (accessTokenResult as any).accessToken || '';
          }
            
          // Lấy thông tin người dùng
          const userInfo = await getUserInfo({});
          console.log("User info:", userInfo);
          
          const zaloId = userInfo.userInfo?.id || '';
          
          // Gửi token vị trí đến backend để xử lý
          const response = await axios({
            method: 'post',
            url: 'https://thiepcuoitoandao.id.vn/zalo/process-location-token',
            data: {
              token: locationResult.token,
              accessToken: accessToken,
              zaloId: zaloId
            },
            headers: {
              'Content-Type': 'application/json'
            }
          });
          
          if (response.data.success && response.data.location) {
            console.log("Location processed successfully:", response.data.location);
            
            const locationData = response.data.location;
            // Set user location with all the available data
            setUserLocation({
              latitude: locationData.latitude,
              longitude: locationData.longitude,
              provider: locationData.provider || 'unknown',
              timestamp: locationData.timestamp || ''
            });
            
            setLocationPermissionGranted(true);
            return true;
          } else {
            console.error("Error processing location:", response.data);
            openSnackbar({
              text: "Không thể lấy vị trí của bạn. Vui lòng thử lại.",
              type: "error",
              duration: 3000
            });
            return false;
          }
        } catch (error) {
          console.error("Error in location processing:", error);
          openSnackbar({
            text: "Đã xảy ra lỗi khi xử lý vị trí",
            type: "error",
            duration: 3000
          });
          return false;
        }
      } else {
        console.error("No location token received");
        openSnackbar({
          text: "Không thể lấy token vị trí",
          type: "error",
          duration: 3000
        });
        return false;
      }
    } catch (error) {
      console.error("Error getting location:", error);
      openSnackbar({
        text: "Không thể lấy quyền truy cập vị trí",
        type: "error",
        duration: 3000
      });
      return false;
    }
  };

  // Lấy thông tin chi tiết đại lý
  const fetchAgentDetail = async () => {
    if (!id) return;
    
    console.log("Fetching agent detail for ID:", id);
    setLoading(true);
    try {
      // Lấy tham số created từ URL nếu có
      const queryParams = new URLSearchParams(location.search);
      const createdParam = queryParams.get('created');
      
      let url = `/agents/${id}`;
      const params: any = {};
      
      // Thêm thông tin vị trí nếu có
      if (userLocation?.latitude && userLocation?.longitude) {
        params.lat = userLocation.latitude;
        params.lng = userLocation.longitude;
      }
      
      // Thêm tham số created nếu có
      if (createdParam) {
        params.created = createdParam;
      }
      
      // Tạo URL với các tham số
      const queryString = new URLSearchParams(params).toString();
      if (queryString) {
        url += `?${queryString}`;
      }
      
      console.log("Making API request to:", apiClient.defaults.baseURL + url);
      const response = await apiClient.get(url);
      console.log('Agent detail response:', response.data);
      
      if (response.data && response.data.data) {
        setAgent(response.data.data);
        
        // Kiểm tra thông tin mã QR
        if (response.data.data.qr_info) {
          setQrCreatedTime(response.data.data.qr_info.created_time);
        }
        
        setError(null);
      } else {
        console.error('Invalid response format:', response.data);
        setError('Dữ liệu đại lý không hợp lệ');
        setAgent(null);
      }
    } catch (error: any) {
      console.error('Error fetching agent detail:', error);
      setError(error.response?.data?.message || 'Không thể tải thông tin đại lý');
      setAgent(null);
    } finally {
      setLoading(false);
    }
  };

  // Lấy địa chỉ đầy đủ của đại lý
  const getFullAddress = () => {
    if (agent?.full_address) {
      return agent.full_address;
    }
    
    // Nếu không, tự tạo từ các thành phần
    const parts: string[] = [];
    if (agent?.address) parts.push(agent.address);
    if (agent?.ward?.name) parts.push(agent.ward.name);
    if (agent?.district?.name) parts.push(agent.district.name);
    if (agent?.province?.name) parts.push(agent.province.name);
    return parts.join(', ');
  };

  // Tính khoảng cách giữa hai điểm (theo công thức Haversine)
  const calculateDistance = (lat1: number, lon1: number, lat2: number, lon2: number) => {
    const R = 6371; // Bán kính trái đất tính bằng km
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = 
      Math.sin(dLat/2) * Math.sin(dLat/2) +
      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
      Math.sin(dLon/2) * Math.sin(dLon/2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    const distance = R * c; // Khoảng cách tính bằng km
    return distance;
  };

  const handleBack = () => {
    navigate(-1);
  };

  const handleCall = () => {
    if (agent?.phone) {
      window.location.href = `tel:${agent.phone}`;
    }
  };

  const handleEmail = () => {
    if (agent?.email) {
      window.location.href = `mailto:${agent.email}`;
    }
  };

  const handleViewMap = () => {
    if (agent?.latitude && agent?.longitude) {
      window.open(`https://maps.google.com/maps?q=${agent.latitude},${agent.longitude}`, '_blank');
    }
  };

  // Hiển thị màn hình yêu cầu cấp quyền
  if (!permissionsChecked && !loading) {
    return (
      <Page>
        <Box className="p-4 flex flex-col items-center justify-center h-full">
          <Text className="text-xl font-bold mb-4 text-center">Cấp quyền truy cập</Text>
          
          <Box className="bg-yellow-50 p-4 rounded-md mb-6 w-full">
            <Text className="text-yellow-700 mb-2">
              Để xem thông tin chi tiết đại lý, bạn cần cấp quyền truy cập:
            </Text>
            <ul className="list-disc pl-5 mb-2">
              <li className="text-yellow-700 flex items-center gap-2">
                <span>Số điện thoại</span>
                {phonePermissionGranted && <Check size={16} className="text-green-600" />}
              </li>
              <li className="text-yellow-700 flex items-center gap-2">
                <span>Vị trí hiện tại</span>
                {locationPermissionGranted && <Check size={16} className="text-green-600" />}
              </li>
            </ul>
            <Text className="text-yellow-700">
              Thông tin này chỉ được sử dụng để tính khoảng cách đến đại lý.
            </Text>
          </Box>
          
          <Button 
            fullWidth 
            onClick={checkPermissions}
            className="mb-3 h-12 bg-green-600 text-white flex items-center justify-center"
          >
            <div className="flex items-center gap-2">
              <Phone size={16} className="flex-shrink-0" />
              <span>Cấp quyền truy cập</span>
            </div>
          </Button>
          
          <Button
            fullWidth
            variant="secondary"
            onClick={handleBack}
            className="h-12 flex items-center justify-center"
          >
            <div className="flex items-center gap-2">
              <ArrowLeft size={16} className="flex-shrink-0" />
              <span>Quay lại</span>
            </div>
          </Button>
        </Box>
      </Page>
    );
  }

  return (
    <Page>
      <Box className="p-4">
        <Button 
          variant="secondary" 
          className="mb-4 h-10 flex items-center justify-center" 
          onClick={handleBack}
          size="small"
        >
          <div className="flex items-center">
            <ArrowLeft size={16} />
            <span className="ml-2">Quay lại</span>
          </div>
        </Button>

        {loading ? (
          <Box className="py-12 flex flex-col items-center justify-center">
            <div className="w-20 h-20 border-4 border-blue-400 border-t-blue-600 rounded-full animate-spin mb-6"></div>
            <Text className="text-gray-600 font-medium text-lg">Đang tải thông tin đại lý...</Text>
            <Text className="text-gray-500 text-sm mt-2">Vui lòng đợi trong giây lát</Text>
          </Box>
        ) : error ? (
          <Box className="bg-red-50 p-6 rounded-lg shadow-md border border-red-100 mb-4">
            <Box className="flex flex-col items-center text-center mb-4">
              <AlertCircle className="text-red-500 mb-3" size={48} />
              <Text className="text-red-700 font-bold text-xl mb-2">Không thể tải thông tin đại lý</Text>
              <Text className="text-red-600 mb-4">{error}</Text>
            </Box>
            
            <Box className="bg-white p-4 rounded-lg border border-red-100 mb-4">
              <Text className="text-gray-700 mb-2 font-medium">Một số lý do có thể xảy ra:</Text>
              <ul className="list-disc pl-5 space-y-1 text-gray-600 mb-3">
                <li>Đại lý này không còn tồn tại</li>
                <li>Kết nối mạng của bạn không ổn định</li>
                <li>Máy chủ đang bảo trì</li>
              </ul>
              <Text className="text-gray-700">
                Bạn có thể quay lại danh sách đại lý để chọn đại lý khác hoặc thử lại.
              </Text>
            </Box>
            
            <Box className="flex gap-3">
              <Button 
                className="flex-1 bg-red-600 text-white h-10 flex items-center justify-center"
                onClick={() => {
                  setLoading(true);
                  setError(null);
                  fetchAgentDetail();
                }}
              >
                <div className="flex items-center">
                  <RefreshCcw size={16} />
                  <span className="ml-2">Thử lại</span>
                </div>
              </Button>
              
              <Button 
                className="flex-1 bg-gray-600 text-white"
                onClick={handleBack}
              >
                <ArrowLeft size={16} className="mr-2 flex-shrink-0" /> <span>Quay lại danh sách</span>
              </Button>
            </Box>
          </Box>
        ) : agent ? (
          <>
            {/* Agent Hero Header - Gradient background with Agent info */}
            <Box className="bg-gradient-to-r from-blue-600 to-green-500 text-white rounded-lg shadow-lg overflow-hidden mb-6">
              {/* Image section */}
              {agent.image ? (
                <div className="relative h-48 w-full">
                  <img 
                    src={agent.image} 
                    alt={agent.name}
                    className="w-full h-full object-cover"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
              ) : (
                <div className="h-32 w-full bg-gradient-to-br from-blue-700 to-green-600 flex items-center justify-center">
                  <Store size={64} className="text-white opacity-80" />
                </div>
              )}
              
              {/* Agent info overlay */}
              <Box className="p-5">
                <Text className="text-2xl font-bold mb-2">{agent.name}</Text>
                
                {agent.status && (
                  <span className="inline-block bg-green-500 bg-opacity-90 text-white text-xs font-bold px-2 py-1 rounded-full shadow-sm mb-3">
                    Đang hoạt động
                  </span>
                )}
                
                {/* Show QR scan badge if accessed from QR code */}
                {isFromQrCode && (
                  <div className="mb-3">
                    <span className="inline-block bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                      <div className="flex items-center">
                        <QrCode size={12} className="mr-1" />
                        <span>Mã QR đã quét</span>
                      </div>
                    </span>
                  </div>
                )}
                
                <Text className="text-white text-opacity-90 mb-2 flex items-center">
                  <MapPin size={16} className="mr-2 flex-shrink-0" /> 
                  <span>{agent.full_address}</span>
                </Text>
                
                {agent.distance !== undefined && (
                  <Text className="text-white text-opacity-90 font-medium flex items-center">
                    <MapPin size={16} className="mr-2 flex-shrink-0" /> 
                    <span>Cách bạn: <span className="font-bold ml-1">{Number(agent.distance).toFixed(2)} km</span></span>
                  </Text>
                )}
              </Box>
            </Box>
            
            {/* QR Code Information - Only shown if accessed from QR */}
            {isFromQrCode && qrCreatedTime && (
              <Box className="bg-yellow-50 p-4 rounded-lg shadow-md border border-yellow-100 mb-6">
                <Box className="flex items-center mb-2">
                  <QrCode className="text-yellow-600 mr-2" size={20} />
                  <Text className="font-bold text-yellow-800">Thông tin mã QR</Text>
                </Box>
                <Box className="space-y-2">
                  <Box className="flex items-start">
                    <Calendar size={16} className="text-yellow-600 mr-2 mt-1 flex-shrink-0" />
                    <Box>
                      <Text className="text-yellow-800 text-sm">Thời gian tạo mã QR:</Text>
                      <Text className="font-medium text-yellow-900">{qrCreatedTime}</Text>
                    </Box>
                  </Box>
                  <Box className="flex items-start">
                    <Clock size={16} className="text-yellow-600 mr-2 mt-1 flex-shrink-0" />
                    <Box>
                      <Text className="text-yellow-800 text-sm">Thời gian quét:</Text>
                      <Text className="font-medium text-yellow-900">{new Date().toLocaleString('vi-VN')}</Text>
                    </Box>
                  </Box>
                  <Text className="text-xs text-yellow-700 mt-2">
                    Mã QR này được tạo bởi hệ thống quản lý đại lý của chúng tôi. 
                    Mọi thắc mắc vui lòng liên hệ với đại lý trực tiếp.
                  </Text>
                </Box>
              </Box>
            )}
            
            {/* Quick Action Buttons */}
            <Box className="flex gap-2 mb-6">
              <Button
                className="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md h-12 flex items-center justify-center"
                onClick={handleCall}
              >
                <div className="flex items-center">
                  <Phone size={18} />
                  <span className="ml-2">Gọi Điện</span>
                </div>
              </Button>
              
              <Button
                className="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md h-12 flex items-center justify-center"
                onClick={handleViewMap}
              >
                <div className="flex items-center">
                  <MapPin size={18} />
                  <span className="ml-2">Xem Bản Đồ</span>
                </div>
              </Button>
            </Box>
            
            {/* Information Sections */}
            <Box className="space-y-4">
              {/* Contact Section */}
              <Box className="bg-white rounded-lg shadow-md overflow-hidden">
                <Box className="bg-blue-50 px-4 py-3 border-l-4 border-blue-500">
                  <Text className="font-bold text-blue-800">Thông tin liên hệ</Text>
                </Box>
                
                <Box className="p-4 space-y-3">
                  {agent.phone && (
                    <Box className="flex items-center">
                      <Phone className="text-gray-500 mr-3 flex-shrink-0" size={20} />
                      <Box>
                        <Text className="text-gray-700 font-medium">Điện thoại</Text>
                        <Text className="text-gray-900">{agent.phone}</Text>
                      </Box>
                    </Box>
                  )}
                  
                  {agent.open_hours && (
                    <Box className="flex items-center">
                      <Clock className="text-gray-500 mr-3 flex-shrink-0" size={20} />
                      <Box>
                        <Text className="text-gray-700 font-medium">Giờ mở cửa</Text>
                        <Text className="text-gray-900">{agent.open_hours}</Text>
                      </Box>
                    </Box>
                  )}
                </Box>
              </Box>
              
              {/* Location Section */}
              <Box className="bg-white rounded-lg shadow-md overflow-hidden">
                <Box className="bg-green-50 px-4 py-3 border-l-4 border-green-500">
                  <Text className="font-bold text-green-800">Địa chỉ chi tiết</Text>
                </Box>
                
                <Box className="p-4">
                  <ul className="space-y-2">
                    {agent.address && (
                      <li className="flex">
                        <span className="h-5 w-5 rounded-full bg-green-100 text-green-800 flex items-center justify-center text-xs font-bold mr-2 flex-shrink-0 mt-0.5">•</span>
                        <Box>
                          <Text className="text-gray-700 font-medium">Địa chỉ</Text>
                          <Text className="text-gray-900">{agent.address}</Text>
                        </Box>
                      </li>
                    )}
                    
                    {agent.ward?.name && (
                      <li className="flex">
                        <span className="h-5 w-5 rounded-full bg-green-100 text-green-800 flex items-center justify-center text-xs font-bold mr-2 flex-shrink-0 mt-0.5">•</span>
                        <Box>
                          <Text className="text-gray-700 font-medium">Phường/Xã</Text>
                          <Text className="text-gray-900">{agent.ward.name}</Text>
                        </Box>
                      </li>
                    )}
                    
                    {agent.district?.name && (
                      <li className="flex">
                        <span className="h-5 w-5 rounded-full bg-green-100 text-green-800 flex items-center justify-center text-xs font-bold mr-2 flex-shrink-0 mt-0.5">•</span>
                        <Box>
                          <Text className="text-gray-700 font-medium">Quận/Huyện</Text>
                          <Text className="text-gray-900">{agent.district.name}</Text>
                        </Box>
                      </li>
                    )}
                    
                    {agent.province?.name && (
                      <li className="flex">
                        <span className="h-5 w-5 rounded-full bg-green-100 text-green-800 flex items-center justify-center text-xs font-bold mr-2 flex-shrink-0 mt-0.5">•</span>
                        <Box>
                          <Text className="text-gray-700 font-medium">Tỉnh/Thành phố</Text>
                          <Text className="text-gray-900">{agent.province.name}</Text>
                        </Box>
                      </li>
                    )}
                  </ul>
                </Box>
              </Box>
              
              {/* Description Section */}
              {agent.description && (
                <Box className="bg-white rounded-lg shadow-md overflow-hidden">
                  <Box className="bg-indigo-50 px-4 py-3 border-l-4 border-indigo-500">
                    <Text className="font-bold text-indigo-800">Giới thiệu</Text>
                  </Box>
                  
                  <Box className="p-4">
                    <Text className="text-gray-700 whitespace-pre-line leading-relaxed">{agent.description}</Text>
                  </Box>
                </Box>
              )}
            </Box>
            
            {/* Back Button */}
            <Button 
              variant="secondary" 
              className="mt-6 w-full h-12 flex items-center justify-center"
              onClick={handleBack}
            >
              <div className="flex items-center">
                <ArrowLeft size={18} />
                <span className="ml-2">Quay lại danh sách</span>
              </div>
            </Button>
          </>
        ) : (
          <Text className="text-center py-4">Không tìm thấy thông tin đại lý.</Text>
        )}
      </Box>
    </Page>
  );
};

export default AgentDetailPage; 