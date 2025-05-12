import React, { useState, useEffect } from 'react';
import { Page, Box, Text, Button, Input, useSnackbar } from 'zmp-ui';
import axios from 'axios';
import { Agent, LocationData, Province, District, Ward } from '@/types';
import AgentCard from '@/components/agent-card';
import { MapPin, Phone, AlertCircle, Store, Clock, SearchIcon, RefreshCcw, Compass, Check } from 'lucide-react';
import { getUserInfo, getPhoneNumber, getAccessToken, getLocation } from "zmp-sdk";
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-hot-toast';
import { Icon } from 'zmp-ui';

const apiClient = axios.create({
  baseURL: 'https://agrijapanvn.com.vn',
  headers: {
    'Content-Type': 'application/json',
  },
});

const AgentsPage: React.FC = () => {
  const { openSnackbar } = useSnackbar();
  const navigate = useNavigate();
  const [agents, setAgents] = useState<any[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [searchTerm, setSearchTerm] = useState<string>('');
  const [selectedProvinceId, setSelectedProvinceId] = useState<number | null>(null);
  const [selectedDistrictId, setSelectedDistrictId] = useState<number | null>(null);
  const [selectedWardId, setSelectedWardId] = useState<number | null>(null);
  const [locationData, setLocationData] = useState<LocationData>({
    provinces: [],
  });
  const [userPhone, setUserPhone] = useState<string | null>(null);
  const [userLocation, setUserLocation] = useState<{latitude: number, longitude: number, provider: string, timestamp: string} | null>(null);
  const [permissionsChecked, setPermissionsChecked] = useState<boolean>(false);
  const [phonePermissionGranted, setPhonePermissionGranted] = useState<boolean>(false);
  const [locationPermissionGranted, setLocationPermissionGranted] = useState<boolean>(false);
  const [nearestAgent, setNearestAgent] = useState<any>(null);

  // Kiểm tra quyền truy cập khi component được mount
  useEffect(() => {
    checkPermissions();
  }, []);

  // Kiểm tra quyền truy cập số điện thoại và vị trí
  const checkPermissions = async () => {
    try {
      // Kiểm tra quyền truy cập số điện thoại
      await checkPhonePermission();
      
      // Kiểm tra quyền truy cập vị trí
      await checkLocationPermission();
      
      setPermissionsChecked(true);
    } catch (error) {
      console.error('Error checking permissions:', error);
      setError('Vui lòng cấp quyền truy cập số điện thoại và vị trí để sử dụng tính năng này.');
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
            url: 'https://agrijapanvn.com.vn/zalo/process-location-token',
            data: {
              token: locationResult.token,
              accessToken: accessToken,
              zaloId: zaloId
            },
            headers: {
              'Content-Type': 'application/json'
            }
          });
          
          console.log("Full location API response:", response.data);
          
          if (response.data.success && response.data.location) {
            console.log("Location processed successfully:", response.data.location);
            
            const locationData = response.data.location;
            // Set user location with all the available data
            setUserLocation({
              latitude: locationData.latitude,
              longitude: locationData.longitude,
              provider: locationData.provider,
              timestamp: locationData.timestamp
            });
            
            setLocationPermissionGranted(true);
            
            // If we have location, fetch agents with location parameters
            fetchAgents();
            
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

  // Lấy dữ liệu địa lý khi đã có quyền truy cập
  useEffect(() => {
    if (permissionsChecked) {
      fetchLocationData();
      fetchAgents();
    }
  }, [permissionsChecked]);

  // Lấy dữ liệu địa lý
  const fetchLocationData = async () => {
    try {
      const response = await apiClient.get('/location/data');
      console.log('Location data:', response.data);
      setLocationData({ provinces: response.data.provinces });
    } catch (error) {
      console.error('Error fetching location data:', error);
      setError('Không thể tải dữ liệu địa lý. Vui lòng thử lại sau.');
    }
  };

  // Lấy danh sách đại lý khi các bộ lọc thay đổi
  useEffect(() => {
    if (permissionsChecked) {
      fetchAgents();
    }
  }, [searchTerm, selectedProvinceId, selectedDistrictId, selectedWardId, permissionsChecked]);

  // Lấy danh sách đại lý
  const fetchAgents = async () => {
    try {
      setLoading(true);
      setError(null);
      
      // Xây dựng URL với các tham số lọc
      let url = '/agents';
      const params = new URLSearchParams();
      
      if (searchTerm) {
        params.append('search', searchTerm);
      }
      
      if (selectedProvinceId) {
        params.append('province_id', selectedProvinceId.toString());
      }
      
      if (selectedDistrictId) {
        params.append('district_id', selectedDistrictId.toString());
      }
      
      if (selectedWardId) {
        params.append('ward_id', selectedWardId.toString());
      }
      
      // Thêm tọa độ nếu người dùng đã cấp quyền vị trí
      if (userLocation) {
        params.append('lat', userLocation.latitude.toString());
        params.append('lng', userLocation.longitude.toString());
      }
      
      if (params.toString()) {
        url += `?${params.toString()}`;
      }
      
      console.log('Fetching agents with URL:', url);
      
      const response = await apiClient.get(url);
      
      setAgents(response.data.data);
      console.log('Fetched agents:', response.data.data);
    } catch (error) {
      console.error('Error fetching agents:', error);
      setError('Đã xảy ra lỗi khi tải danh sách đại lý');
    } finally {
      setLoading(false);
    }
  };

  // Lấy tỉnh/thành phố hiện tại
  const getCurrentProvince = (): Province | undefined => {
    if (!selectedProvinceId) return undefined;
    return locationData.provinces.find(p => p.id === selectedProvinceId);
  };

  // Lấy quận/huyện hiện tại
  const getCurrentDistrict = (): District | undefined => {
    const province = getCurrentProvince();
    if (!province || !selectedDistrictId) return undefined;
    return province.districts?.find(d => d.id === selectedDistrictId);
  };

  // Xử lý khi thay đổi tỉnh/thành phố
  const handleProvinceChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const provinceId = e.target.value ? parseInt(e.target.value) : null;
    setSelectedProvinceId(provinceId);
    setSelectedDistrictId(null);
    setSelectedWardId(null);
  };

  // Xử lý khi thay đổi quận/huyện
  const handleDistrictChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const districtId = e.target.value ? parseInt(e.target.value) : null;
    setSelectedDistrictId(districtId);
    setSelectedWardId(null);
  };

  // Xử lý khi thay đổi phường/xã
  const handleWardChange = (e: React.ChangeEvent<HTMLSelectElement>) => {
    const wardId = e.target.value ? parseInt(e.target.value) : null;
    setSelectedWardId(wardId);
  };

  // Xử lý khi submit form tìm kiếm
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    fetchAgents();
  };

  // Xử lý khi reset form tìm kiếm
  const handleReset = () => {
    setSearchTerm('');
    setSelectedProvinceId(null);
    setSelectedDistrictId(null);
    setSelectedWardId(null);
    fetchAgents();
  };

  // Xử lý khi click vào đại lý
  const handleAgentClick = async (agentId: number) => {
    // Điều hướng đến trang chi tiết mà không cần kiểm tra quyền truy cập
    // Trang chi tiết sẽ tự xử lý việc kiểm tra quyền nếu cần
    console.log("Navigating to agent detail with ID:", agentId);
    navigate(`/agents/${agentId}`);
  };

  // Thêm hàm tìm đại lý gần nhất
  const findNearestAgent = async () => {
    if (!userLocation?.latitude || !userLocation?.longitude) {
      console.log('Không có thông tin vị trí người dùng');
      return;
    }
    
    try {
      setLoading(true);
      
      console.log(`Tìm đại lý gần nhất tại: ${userLocation.latitude}, ${userLocation.longitude}`);
      
      const response = await apiClient.post('/agents/nearest', {
        lat: userLocation.latitude,
        lng: userLocation.longitude
      });
      
      if (response.data.success && response.data.agent) {
        setNearestAgent(response.data.agent);
        console.log('Đại lý gần nhất:', response.data.agent);
        
        // Hiển thị thông báo cho người dùng
        openSnackbar({
          text: `Đại lý gần nhất: ${response.data.agent.name} - Cách bạn ${response.data.agent.distance} km`,
          type: 'success',
          duration: 5000,
          action: {
            text: 'Xem',
            onClick: () => navigate(`/agents/${response.data.agent.id}`)
          }
        });
      }
    } catch (error) {
      console.error('Lỗi khi tìm đại lý gần nhất:', error);
      openSnackbar({
        text: 'Không thể tìm đại lý gần nhất. Vui lòng thử lại sau.',
        type: 'error',
        duration: 3000
      });
    } finally {
      setLoading(false);
    }
  };

  // Gọi hàm tìm đại lý gần nhất khi có vị trí
  useEffect(() => {
    if (userLocation?.latitude && userLocation?.longitude && locationPermissionGranted) {
      findNearestAgent();
    }
  }, [locationPermissionGranted, userLocation]);

  // Hiển thị màn hình yêu cầu cấp quyền
  if (!permissionsChecked && !loading || (!phonePermissionGranted || !locationPermissionGranted)) {
    return (
      <Page>
        <Box className="p-4 flex flex-col items-center justify-center h-full">
          <Text className="text-xl font-bold mb-4 text-center">Cấp quyền truy cập</Text>
          
          <Box className="bg-yellow-50 p-4 rounded-md mb-6 w-full">
            <Text className="text-yellow-700 mb-2">
              Để xem danh sách đại lý, bạn cần cấp quyền truy cập:
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
        </Box>
      </Page>
    );
  }

  return (
    <Page>
      <Box className="p-4">
        <Text.Title className="mb-4 text-2xl">Tìm kiếm đại lý</Text.Title>
        
        {/* Search Form - Updated Design */}
        <form 
          onSubmit={handleSubmit} 
          className="bg-white rounded-lg shadow-lg p-4 mb-6"
        >
          <Box className="mb-4">
            <Box className="mb-1">
              <Text className="font-medium text-gray-700">Tỉnh/Thành phố</Text>
            </Box>
            <select 
              value={selectedProvinceId || ''} 
              onChange={handleProvinceChange}
              className="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
            >
              <option value="">Chọn Tỉnh/Thành phố</option>
              {locationData.provinces.map(province => (
                <option key={province.id} value={province.id}>
                  {province.name} {province.agent_count > 0 ? `(${province.agent_count} đại lý)` : '(Không có đại lý)'}
                </option>
              ))}
            </select>
          </Box>
          
          {selectedProvinceId && (
            <Box className="mb-4">
              <Box className="mb-1">
                <Text className="font-medium text-gray-700">Quận/Huyện</Text>
              </Box>
              <select 
                value={selectedDistrictId || ''} 
                onChange={handleDistrictChange}
                className="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
              >
                <option value="">Chọn Quận/Huyện</option>
                {getCurrentProvince()?.districts?.map(district => (
                  <option key={district.id} value={district.id}>
                    {district.name} {district.agent_count > 0 ? `(${district.agent_count} đại lý)` : '(Không có đại lý)'}
                  </option>
                ))}
              </select>
            </Box>
          )}
          
          {selectedDistrictId && (
            <Box className="mb-4">
              <Box className="mb-1">
                <Text className="font-medium text-gray-700">Phường/Xã</Text>
              </Box>
              <select 
                value={selectedWardId || ''} 
                onChange={handleWardChange}
                className="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200 transition-all"
              >
                <option value="">Chọn Phường/Xã</option>
                {getCurrentDistrict()?.wards?.map(ward => (
                  <option key={ward.id} value={ward.id}>
                    {ward.name} {ward.agent_count > 0 ? `(${ward.agent_count} đại lý)` : '(Không có đại lý)'}
                  </option>
                ))}
              </select>
            </Box>
          )}
          
          <Box className="flex gap-2">
            <Button 
              htmlType="submit"
              className="flex-1 h-12 bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-md flex items-center justify-center"
            >
              <div className="flex items-center">
                <SearchIcon size={16} /> 
                <span className="ml-2">Tìm kiếm</span>
              </div>
            </Button>
            <Button 
              htmlType="button" 
              onClick={handleReset}
              className="flex-1 h-12 bg-gradient-to-r from-gray-200 to-gray-300 text-gray-700 shadow-md flex items-center justify-center"
            >
              <div className="flex items-center">
                <RefreshCcw size={16} /> 
                <span className="ml-2">Đặt lại</span>
              </div>
            </Button>
          </Box>
        </form>
        
        {userLocation && (
          <Box
            className="bg-gradient-to-r from-blue-100 to-indigo-100 rounded-lg p-4 mb-6 shadow-md border border-blue-200"
          >
            <Text className="font-medium text-blue-800 mb-2 flex items-center">
              <MapPin className="mr-2 h-5 w-5 text-blue-600" /> Vị trí của bạn
            </Text>
            <Text className="text-blue-700 text-sm">
              Đang hiển thị đại lý gần vị trí của bạn. Khoảng cách sẽ được tính từ vị trí hiện tại của bạn đến các đại lý.
            </Text>
            
            <Button
              className="mt-3 bg-blue-600 text-white w-full h-12 flex items-center justify-center"
              onClick={findNearestAgent}
            >
              <div className="flex items-center">
                <Compass size={16} /> 
                <span className="ml-2">Tìm đại lý gần nhất</span>
              </div>
            </Button>
          </Box>
        )}
        
        {/* Results Header */}
        <Box className="flex items-center justify-between mb-4">
          <Text.Title className="text-xl font-bold">
            Kết quả {agents.length > 0 ? `(${agents.length})` : ''}
          </Text.Title>
          
          {agents.length > 0 && (
            <Box className="flex items-center bg-green-100 rounded-full px-3 py-1 text-green-800 text-sm font-medium">
              <Check size={14} className="mr-1 flex-shrink-0" /> <span>Đã tìm thấy {agents.length} đại lý</span>
            </Box>
          )}
        </Box>
        
        {/* Hiển thị kết quả */}
        <Box>
          {loading ? (
            <Box className="py-8 flex flex-col items-center justify-center">
              <div className="w-16 h-16 border-4 border-blue-400 border-t-blue-600 rounded-full animate-spin mb-4"></div>
              <Text className="text-gray-600 font-medium">Đang tải danh sách đại lý...</Text>
            </Box>
          ) : error ? (
            <Box className="bg-red-50 p-6 rounded-lg shadow-md flex flex-col items-center border border-red-100">
              <AlertCircle className="text-red-500 mb-3" size={48} />
              <Text className="text-red-700 font-bold text-lg mb-2">Không thể tải danh sách đại lý</Text>
              <Text className="text-red-600 text-center mb-4">{error}</Text>
              <Button
                className="bg-red-600 text-white"
                onClick={() => {
                  setLoading(true);
                  setError(null);
                  fetchAgents();
                }}
              >
                <div className="flex items-center">
                  <RefreshCcw size={16} /> 
                  <span className="ml-2">Thử lại</span>
                </div>
              </Button>
            </Box>
          ) : agents.length === 0 ? (
            <Box className="bg-gray-50 p-6 rounded-lg shadow-md flex flex-col items-center border border-gray-200">
              <Store className="text-gray-400 mb-3" size={48} />
              <Text className="text-gray-700 font-bold text-lg mb-2">Không tìm thấy đại lý nào</Text>
              <Text className="text-gray-600 text-center mb-4">
                Hãy thử tìm kiếm với điều kiện khác hoặc xem danh sách đại lý gần nhất với vị trí của bạn.
              </Text>
              <Button
                className="bg-blue-600 text-white"
                onClick={handleReset}
              >
                <div className="flex items-center">
                  <RefreshCcw size={16} /> 
                  <span className="ml-2">Đặt lại bộ lọc</span>
                </div>
              </Button>
            </Box>
          ) : (
            <>
              {agents.map((agent) => (
                <Box 
                  key={agent.id} 
                  className="bg-white rounded-lg shadow-lg overflow-hidden mb-4 hover:shadow-xl transition-all duration-300 cursor-pointer"
                  onClick={() => handleAgentClick(agent.id)}
                >
                  {/* Agent Header with gradient background or image */}
                  <Box className="relative">
                    {agent.image ? (
                      <div className="h-32 w-full bg-gradient-to-r from-blue-500 to-green-400 relative">
                        <img 
                          src={agent.image} 
                          alt={agent.name} 
                          className="w-full h-full object-cover mix-blend-overlay"
                        />
                      </div>
                    ) : (
                      <div className="h-24 w-full bg-gradient-to-r from-blue-500 to-green-400 flex items-center justify-center">
                        <Store size={48} className="text-white opacity-90" />
                      </div>
                    )}
                    
                    {/* Status badge */}
                    {agent.status && (
                      <span className="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-md">
                        Hoạt động
                      </span>
                    )}
                  </Box>
                  
                  {/* Agent Content */}
                  <Box className="p-4">
                    <Text className="font-bold text-xl mb-2 text-gray-800">{agent.name}</Text>
                    
                    <Box className="flex items-start mb-3">
                      <MapPin className="text-gray-500 mr-2 flex-shrink-0 mt-1" size={16} />
                      <Text className="text-gray-600 text-sm line-clamp-2">{agent.full_address}</Text>
                    </Box>
                    
                    {agent.distance !== undefined && (
                      <Box className="bg-blue-50 rounded-md p-2 mb-3">
                        <Text className="text-blue-700 text-sm font-medium flex items-center">
                          <MapPin className="inline-block mr-1 flex-shrink-0" size={16} /> 
                          <span>Cách bạn: <span className="font-bold ml-1">{Number(agent.distance).toFixed(2)} km</span></span>
                        </Text>
                      </Box>
                    )}

                    {agent.open_hours && (
                      <Box className="flex items-center mb-3">
                        <Clock className="text-gray-500 mr-2 flex-shrink-0" size={16} />
                        <Text className="text-gray-600 text-sm">{agent.open_hours}</Text>
                      </Box>
                    )}
                    
                    {/* Divider */}
                    <Box className="border-t border-gray-100 my-3"></Box>
                    
                    {/* Action Buttons */}
                    <Box className="flex justify-between gap-2">
                      <Button
                        className="flex-1 bg-gradient-to-r from-green-500 to-green-600 text-white shadow-md h-10 flex items-center justify-center"
                        onClick={(e) => {
                          e.stopPropagation();
                          window.location.href = `tel:${agent.phone}`;
                        }}
                      >
                        <div className="flex items-center">
                          <Phone size={16} /> 
                          <span className="ml-2">Gọi Điện</span>
                        </div>
                      </Button>
                      
                      <Button
                        className="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md h-10 flex items-center justify-center"
                        onClick={(e) => {
                          e.stopPropagation();
                          window.location.href = `https://www.google.com/maps/search/?api=1&query=${agent.latitude},${agent.longitude}`;
                        }}
                      >
                        <div className="flex items-center">
                          <MapPin size={16} /> 
                          <span className="ml-2">Xem Bản Đồ</span>
                        </div>
                      </Button>
                    </Box>
                  </Box>
                </Box>
              ))}
            </>
          )}
        </Box>
      </Box>
    </Page>
  );
};

export default AgentsPage; 