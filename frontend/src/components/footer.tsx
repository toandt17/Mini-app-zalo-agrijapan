import { CartIcon, CategoryIcon, GameIcon, HomeIcon, PackageIcon, UserIcon } from "./vectors";
import HorizontalDivider from "./horizontal-divider";
import { useAtomValue } from "jotai";
import { cartState } from "@/state";
import TransitionLink from "./transition-link";
import { useRouteHandle } from "@/hooks";
import Badge from "./badge";
import { MessageCircle } from "lucide-react";
import { messagesState } from "@/state";
import { Icon } from "zmp-ui";

const NAV_ITEMS = [
  {
    name: "Trang chủ",
    path: "/",
    icon: (props) => <HomeIcon {...props} />,
  },
  {
    name: "Danh mục",
    path: "/categories",
    icon: (props) => <CategoryIcon {...props} />,
  },
  {
    name: "Trò chơi",
    path: "/games",
    icon: (props) =>  <GameIcon {...props} />, 
  },
  {
    name: "Liên hệ",
    path: "/orders",
    icon: (props) => <PackageIcon {...props} />,
  },
  {
    name: "Tin nhắn",
    path: "https://zalo.me/4595954910489503839",
    icon: (props) => <MessageCircle {...props} />,
  },
  {
    name: "Tài khoản",
    path: "/account",
    icon: (props) => <UserIcon {...props} />,
  },
];
  
export default function Footer() {
  const [handle] = useRouteHandle();

  if (!handle?.noFooter) {
    return (
      <>
        <HorizontalDivider />
        <div
          className="w-full px-4 pt-2 grid pb-sb"
          style={{
            gridTemplateColumns: `repeat(${NAV_ITEMS.length}, 1fr)`,
          }}
        >
          {NAV_ITEMS.map((item) => {
            return (
              <TransitionLink
                to={item.path}
                key={item.path}
                className="flex flex-col items-center space-y-0.5 p-1 pb-0.5 cursor-pointer active:scale-105"
              >
                {({ isActive }) => (
                  <>
                    <div className="w-6 h-6 flex justify-center items-center">
                      <item.icon 
                        size={24} 
                        color={isActive ? "#22c55e" : "#6b7280"} // Màu xanh lá khi active, xám khi không
                        strokeWidth={isActive ? 2 : 1.5} 
                      />
                    </div>


                    <div
                      className={`text-2xs ${isActive ? "text-primary" : ""}`}
                    >
                      {item.name}
                    </div>
                  </>
                )}
              </TransitionLink>
            );
          })}
        </div>
      </>
    );
  }
}
