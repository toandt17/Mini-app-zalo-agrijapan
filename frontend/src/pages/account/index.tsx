import { useState, useEffect } from "react";
import { Icon } from "zmp-ui";
import Section from "@/components/section";
import TransitionLink from "@/components/transition-link";
import { getUserInfo } from "zmp-sdk";
import { saveUser } from "@/api/gameApi";

export default function AccountPage() {
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const [userData, setUserData] = useState({
    id: 0,
    name: "",
    avatar: "",
    points: 0,
    zaloId: ""
  });
  const [loading, setLoading] = useState(true);

  // Lấy thông tin người dùng khi trang tải
  useEffect(() => {
    async function fetchUserData() {
      try {
        // Kiểm tra nếu đang chạy trong trình duyệt (dev mode)
        const isDev = !window.ZJSBridge;
        
        if (isDev) {
          // Dev mode - sử dụng dữ liệu mẫu
          setUserData({
            id: 1,
            name: "Người dùng (Dev)",
            avatar: "",
            points: 250,
            zaloId: "dev_user"
          });
          setIsLoggedIn(true);
          setLoading(false);
        } else {
          // Production - lấy thông tin từ Zalo
          const result = await getUserInfo({});
          
          if (result && result.userInfo) {
            // Lưu thông tin người dùng vào hệ thống
            const saveResult = await saveUser({
              zaloId: result.userInfo.id,
              name: result.userInfo.name || "",
              avatar: result.userInfo.avatar || "",
              idByOA: result.userInfo.idByOA || "",
              followedOA: result.userInfo.followedOA || false,
              isSensitive: result.userInfo.isSensitive || false
            });
            
            if (saveResult.success && saveResult.user) {
              setUserData({
                id: saveResult.user.id,
                name: saveResult.user.name || "Người dùng",
                avatar: saveResult.user.avatar || "",
                points: saveResult.user.points || 0,
                zaloId: result.userInfo.id
              });
              setIsLoggedIn(true);
            }
          }
          setLoading(false);
        }
      } catch (error) {
        console.error("Lỗi khi lấy thông tin người dùng:", error);
        setLoading(false);
      }
    }
    
    fetchUserData();
  }, []);

  // Danh sách các menu chức năng
  const menuItems = [
    {
      icon: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M12 2a9 9 0 100 18 9 9 0 000-18zM12 6a3 3 0 110 6 3 3 0 010-6z M6.5 19C7.9 17.7 9.8 17 12 17s4.1.7 5.5 2"/></svg>`,
      title: "Thông tin tài khoản",
      route: "/account/profile"
    },
    {
      icon: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="1.5" fill="none"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>`,
      title: "Lịch sử hoạt động",
      route: "/account/games"
    },
    {
      icon: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M20 12v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 010-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 000-5C13 2 12 7 12 7z"/></svg>`,
      title: "Quà tặng của tôi",
      route: "/account/gifts"
    },
    {
      icon: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="1.5" fill="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`,
      title: "Tích điểm của tôi",
      route: "/account/points",
      showPoints: true
    },
    {
      icon: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="1.5" fill="none"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>`,
      title: "Liên hệ",
      route: "/orders"
    }
  ];

  return (
    <div className="w-full h-full flex flex-col bg-gray-50">
      <div className="flex-1 overflow-y-auto">
        {/* Header với thông tin người dùng */}
        <div className="bg-gradient-to-br from-green-500 to-green-700 text-white px-4 pt-8 pb-16 relative overflow-hidden">
          {/* Pattern overlay cho header */}
          <div className="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
              <defs>
                <pattern id="pattern" width="8" height="8" patternUnits="userSpaceOnUse">
                  <circle cx="1" cy="1" r="1" fill="white" />
                </pattern>
              </defs>
              <rect width="100%" height="100%" fill="url(#pattern)" />
            </svg>
          </div>
          
          {/* Thông tin người dùng */}
          {isLoggedIn ? (
            <div className="flex items-center relative z-10">
              <div className="w-[80px] h-[80px] shadow-lg rounded-full overflow-hidden border-2 border-white">
                {userData.avatar ? (
                  <img 
                    src={userData.avatar} 
                    alt={userData.name} 
                    className="w-full h-full object-cover"
                  />
                ) : (
                  <div className="bg-green-800 w-full h-full flex items-center justify-center text-2xl font-bold">
                    {userData.name.charAt(0).toUpperCase()}
                  </div>
                )}
              </div>
              <div className="ml-4">
                <h1 className="text-2xl font-bold">{userData.name}</h1>
                <div className="flex items-center mt-2.5 bg-white bg-opacity-25 backdrop-blur-sm rounded-full px-4 py-2 shadow-sm transform transition-all duration-300 hover:scale-105">
                  <span className="text-yellow-300 mr-2" dangerouslySetInnerHTML={{ __html: `<svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="yellow"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>` }}></span>
                  <span className="font-semibold">{userData.points} điểm</span>
                </div>
              </div>
              
              {/* Decoration element */}
              <div className="absolute -bottom-10 -right-5 w-32 h-32 bg-white bg-opacity-10 rounded-full"></div>
            </div>
          ) : (
            <div className="relative z-10 backdrop-blur-sm bg-white bg-opacity-10 p-5 rounded-xl">
              <h1 className="text-2xl font-bold">Chào mừng bạn!</h1>
              <p className="mt-2 opacity-90">Đăng nhập để tích điểm đổi quà</p>
              <button className="mt-4 bg-white text-green-700 px-6 py-2 rounded-full font-medium shadow-md hover:shadow-lg transition-all duration-300">
                Đăng nhập ngay
              </button>
            </div>
          )}
        </div>

        {/* Menu chức năng - đẩy lên trên để overlap với header */}
        <div className="px-4 -mt-10 mb-6 relative z-20">
          <div className="bg-white rounded-2xl shadow-lg overflow-hidden">
            {menuItems.map((item, index) => (
              <TransitionLink
                key={index}
                to={item.route}
                className="flex items-center justify-between py-4 px-5 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors duration-200"
              >
                <div className="flex items-center">
                  <div className="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 shadow-sm">
                    <span dangerouslySetInnerHTML={{ __html: item.icon }}></span>
                  </div>
                  <span className="ml-3.5 text-base font-medium text-gray-800">{item.title}</span>
                </div>
                <div className="flex items-center">
                  {item.showPoints && isLoggedIn && (
                    <span className="text-base text-green-600 mr-3 font-semibold">{userData.points} điểm</span>
                  )}
                  <span className="text-gray-400 transform transition-transform duration-200 group-hover:translate-x-1" dangerouslySetInnerHTML={{ __html: `<svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><polyline points="9 18 15 12 9 6"/></svg>` }}></span>
                </div>
              </TransitionLink>
            ))}
          </div>
        </div>

        {/* Thêm phần xu hướng và quà hot */}
        <div className="px-4 mb-8">
          <h3 className="text-lg font-semibold text-gray-800 mb-4">Quà hot</h3>
          <div className="grid grid-cols-2 gap-4">
            {[1, 2].map((item) => (
              <div key={item} className="bg-white rounded-xl shadow-sm overflow-hidden transform transition-all duration-300 hover:shadow-md hover:-translate-y-1">
                <div className="h-32 bg-gray-200">
                  <img src={`/placeholder-gift-${item}.jpg`} alt="Quà hot" className="w-full h-full object-cover" />
                </div>
                <div className="p-3">
                  <h4 className="font-medium text-sm">Quà tặng hấp dẫn #{item}</h4>
                  <div className="flex items-center mt-2 justify-between">
                    <span className="text-xs text-green-600 font-semibold">300 điểm</span>
                    <button className="bg-green-100 text-green-700 text-xs rounded-full px-3 py-1">Đổi ngay</button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
} 