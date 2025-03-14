import { useState } from "react";
import { Icon } from "zmp-ui";
import Section from "@/components/section";
import HorizontalDivider from "@/components/horizontal-divider";
import { formatPrice } from "@/utils/format";
import TransitionLink from "@/components/transition-link";

export default function AccountPage() {
  // Mock user data - in a real app, you would fetch this from API
  const [isLoggedIn, setIsLoggedIn] = useState(false);
  const user = isLoggedIn ? {
    name: "Nguyễn Văn A",
    phone: "0987654321",
    points: 100
  } : null;

  return (
    <div className="w-full h-full flex flex-col">
      <div className="flex-1 overflow-y-auto px-4 py-2 space-y-3">
        {/* Đăng ký thành viên banner */}
        {!isLoggedIn && (
          <div className="bg-green-600 text-white rounded-lg p-4">
            <h3 className="text-lg font-medium">Đăng ký thành viên</h3>
            <p className="text-sm mt-1">Tích điểm đổi thưởng, mở rộng tiện ích</p>
          </div>
        )}

        {/* Cá nhân section */}
        <Section title="Cá nhân" className="rounded-lg">
          <div className="divide-y divide-gray-100">
            {/* Thông tin tài khoản */}
            <TransitionLink 
              to="/account/profile" 
              className="flex items-center justify-between py-3 px-4"
            >
              <div className="flex items-center space-x-3">
                <div className="w-6 h-6 flex items-center justify-center">
                  <Icon icon="zi-user" />
                </div>
                <span className="text-sm">Thông tin tài khoản</span>
              </div>
              <Icon icon="zi-chevron-right" />
            </TransitionLink>

            {/* Lịch sử sự kiện mini game - thay đổi từ Lịch sử đơn hàng */}
            <TransitionLink 
              to="/account/games" 
              className="flex items-center justify-between py-3 px-4"
            >
              <div className="flex items-center space-x-3">
                <div className="w-6 h-6 flex items-center justify-center">
                  <Icon icon="zi-game" />
                </div>
                <span className="text-sm">Lịch sử sự kiện mini game</span>
              </div>
              <Icon icon="zi-chevron-right" />
            </TransitionLink>

            {/* Mã ưu đãi của tôi */}
            <TransitionLink 
              to="/account/vouchers" 
              className="flex items-center justify-between py-3 px-4"
            >
              <div className="flex items-center space-x-3">
                <div className="w-6 h-6 flex items-center justify-center">
                  <Icon icon="zi-discount" />
                </div>
                <span className="text-sm">Mã ưu đãi của tôi</span>
              </div>
              <Icon icon="zi-chevron-right" />
            </TransitionLink>
            <TransitionLink to="/ord" className="flex items-center justify-between py-3 px-4">
              <div className="flex items-center space-x-3">
                <div className="w-6 h-6 flex items-center justify-center">
                  <Icon icon="zi-phone" />
                </div>
                <span className="text-sm">Liên hệ</span>
              </div>
              <Icon icon="zi-chevron-right" />
            </TransitionLink>
          </div>
        </Section>

        {/* Powered by section */}
        <div className="flex justify-center items-center py-6">
          <div className="text-gray-400 text-xs flex items-center">
            <div className="mr-2 rounded-full bg-gray-100 p-1">
              <img src="/logo-small.png" alt="Logo" className="w-5 h-5" />
            </div>
            POWERED BY MASTERPRO
          </div>
        </div>

        {isLoggedIn ? (
          <button 
            onClick={() => setIsLoggedIn(false)}
            className="w-full py-3 text-center text-sm font-medium text-red-500 bg-white rounded-lg border border-red-500"
          >
            Đăng xuất
          </button>
        ) : (
          <button 
            onClick={() => setIsLoggedIn(true)}
            className="w-full py-3 text-center text-sm font-medium text-white bg-green-600 rounded-lg"
          >
            Đăng nhập
          </button>
        )}
      </div>
    </div>
  );
} 