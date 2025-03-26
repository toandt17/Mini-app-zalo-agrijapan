import Layout from "@/components/layout";
import CartPage from "@/pages/cart";
import CategoryDetailPage from "@/pages/catalog/category-detail";
import CategoryListPage from "@/pages/catalog/category-list";
import ProductDetailPage from "@/pages/catalog/product-detail";
import HomePage from "@/pages/home";
import ProfilePage from "@/pages/profile";
import SearchPage from "@/pages/search";
import { createBrowserRouter } from "react-router-dom";
import { getBasePath } from "@/utils/zma";
import OrdersPage from "./pages/orders";
import ShippingAddressPage from "./pages/cart/shipping-address";
import StationsPage from "./pages/cart/stations";
import OrderDetailPage from "./pages/orders/detail";
import ProfileEditorPage from "./pages/profile/editor";
import AccountPage from "./pages/account";
import AccountProfilePage from "./pages/account/profile";
import GamesPage from "./pages/games";
import LuckyWheelPage from "./pages/games/lucky-wheel";
import CheckInPage from "./pages/games/check-in";
import QuizPage from "./pages/games/quiz";
import GameHistoryPage from "./pages/account/games";
import UserGiftsPage from "./pages/account/gifts";
import UserPointsPage from "./pages/account/points";
import AgentsPage from "./pages/agents";
import AgentDetailPage from "./pages/agents/detail";
import Quiz from './pages/games/quiz';
import Mission from './pages/games/mission';

const router = createBrowserRouter(
  [
    {
      path: "/",
      element: <Layout />,
      children: [
        {
          path: "/",
          element: <HomePage />,
          handle: {
            logo: true,
            search: true,
          },
        },
        {
          path: "/categories",
          element: <CategoryListPage />,
          handle: {
            title: "Danh mục",
            noBack: true,
          },
        },
        {
          path: "/orders/:status?",
          element: <OrdersPage />,
          handle: {
            title: "Liên hệ",
          },
        },
        {
          path: "/order/:id",
          element: <OrderDetailPage />,
          handle: {
            title: "Thông tin đơn hàng",
          },
        },
        {
          path: "/cart",
          element: <CartPage />,
          handle: {
            title: "Giỏ hàng",
            noBack: true,
            noFloatingCart: true,
          },
        },
        {
          path: "/shipping-address",
          element: <ShippingAddressPage />,
          handle: {
            title: "Địa chỉ nhận hàng",
            noFooter: true,
            noFloatingCart: true,
          },
        },
        {
          path: "/stations",
          element: <StationsPage />,
          handle: {
            title: "Điểm nhận hàng",
            noFooter: true,
          },
        },
        {
          path: "/profile",
          element: <ProfilePage />,
          handle: {
            logo: true,
          },
        },
        {
          path: "/profile/edit",
          element: <ProfileEditorPage />,
          handle: {
            title: "Thông tin tài khoản",
            noFooter: true,
            noFloatingCart: true,
          },
        },
        {
          path: "/category/:id",
          element: <CategoryDetailPage />,
          handle: {
            search: true,
            title: ({ categories, params }) =>
              categories.find((c) => String(c.id) === params.id)?.name,
          },
        },
        {
          path: "/product/:id",
          element: <ProductDetailPage />,
          handle: {
            scrollRestoration: 0, // when user selects another product in related products, scroll to the top of the page
            noFloatingCart: true,
          },
        },
        {
          path: "/search",
          element: <SearchPage />,
          handle: {
            search: true,
            title: "Tìm kiếm",
            noFooter: true,
          },
        },
        {
          path: "/account",
          element: <AccountPage />,
          handle: {
            title: "Tài khoản",
            noBack: true,
          },
        },
        {
          path: "/account/profile",
          element: <AccountProfilePage />,
          handle: {
            title: "Thông tin tài khoản",
            noFooter: true,
          },
        },
        {
          path: "/account/games",
          element: <GameHistoryPage />,
          handle: {
            title: "Lịch sử hoạt động",
            noFooter: true,
          },
        },
        {
          path: "/account/gifts",
          element: <UserGiftsPage />,
          handle: {
            title: "Quà tặng của tôi",
            noFooter: true,
          },
        },
        {
          path: "/account/points",
          element: <UserPointsPage />,
          handle: {
            title: "Tích điểm của tôi",
            noFooter: true,
          },
        },
        {
          path: "/games",
          element: <GamesPage />,
          handle: {
            title: "Mini Games",
            noFooter: false,
          },
        },
        {
          path: "/games/lucky-wheel",
          element: <LuckyWheelPage />,
          handle: {
            title: "Vòng Quay May Mắn",
            noFooter: true,
          },
        },
        {
          path: "/games/check-in",
          element: <CheckInPage />,
          handle: {
            title: "Điểm Danh Hàng Ngày",
            noFooter: true,
          },
        },
        {
          path: "/games/quiz",
          element: <QuizPage />,
          handle: {
            title: "Trả Lời Câu Hỏi",
            noFooter: true,
          },
        },
        {
          path: "/agents",
          element: <AgentsPage />,
          handle: {
            title: "Đại lý gần bạn",
          },
        },
        {
          path: "/agents/:id",
          element: <AgentDetailPage />,
          handle: {
            title: "Chi tiết đại lý",
          },
        },
        {
          path: '/quiz',
          element: <Quiz />,
        },
        {
          path: '/games/mission',
          element: <Mission />,
        },
      ],
    },
  ],
  { basename: getBasePath() }
);

export default router;
