import { atom } from "jotai";
import { atomFamily, atomWithStorage, loadable, unwrap } from "jotai/utils";
import type {
  Cart,
  Category,
  Delivery,
  Location,
  Order,
  OrderStatus,
  Product,
  ShippingAddress,
  Station,
  UserInfo,
  GameActivity,
  CheckInStatus,
  QuizQuestion,
  LuckyWheelData
} from "./types.d";
import { requestWithFallback } from "@/utils/request";
import { getLocation, getPhoneNumber, getSetting, getUserInfo } from "zmp-sdk";
import toast from "react-hot-toast";
import { calculateDistance } from "./utils/location";
import { formatDistant } from "./utils/format";
import CONFIG from "./config";
import axios from 'axios';

export const userInfoKeyState = atom(0);

export const userInfoState = atom<Promise<UserInfo>>(async (get) => {
  get(userInfoKeyState);

  const savedUserInfo = localStorage.getItem(CONFIG.STORAGE_KEYS.USER_INFO);
  if (savedUserInfo) {
    return JSON.parse(savedUserInfo);
  }

  const {
    authSetting: {
      "scope.userInfo": grantedUserInfo,
      "scope.userPhonenumber": grantedPhoneNumber,
    },
  } = await getSetting({});
  const isDev = !window.ZJSBridge;
  if (grantedUserInfo || isDev) {
    const { userInfo } = await getUserInfo({});
    const phone =
      grantedPhoneNumber || isDev 
        ? await get(phoneState)
        : "";                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               
    return {
      name: userInfo.name,                 
      avatar: userInfo.avatar,
      phone,
      email: "",
      address: "",
    };
  }
});

export const loadableUserInfoState = loadable(userInfoState);

export const phoneState = atom(async () => {
  let phone = "";
  try {
    const { token } = await getPhoneNumber({});
    toast(
      "Đã lấy được token chứa số điện thoại người dùng. Phía tích hợp cần decode token này ở server. Giả lập số điện thoại 0912345678...",
      {
        icon: "ℹ",
        duration: 10000,
      }
    );
    await new Promise((resolve) => setTimeout(resolve, 1000));
    phone = "0912345678";
    // End demo
  } catch (error) {
    console.warn(error);
  }
  return phone;
});

export const bannersState = atom(() =>
  requestWithFallback<string[]>("/banners", [])
);

export const tabsState = atom(["Tất cả", "Nam", "Nữ", "Trẻ em"]);

export const selectedTabIndexState = atom(0);

export const categoriesState = atom(() =>
  requestWithFallback<Category[]>("/categories", [])
);

export const categoriesStateUpwrapped = unwrap(
  categoriesState,
  (prev) => prev ?? []
);

export const productsState = atom(async (get) => {
  const categories = await get(categoriesState);
  const products = await requestWithFallback<
    (Product & { categoryId: number })[]
  >("/products", []);
  return products.map((product) => ({
    ...product,
    category: categories.find(
      (category) => category.id === product.categoryId
    )!,
  }));
});

export const flashSaleProductsState = atom((get) => get(productsState));

export const recommendedProductsState = atom((get) => get(productsState));

export const productState = atomFamily((id: number) =>
  atom(async (get) => {
    const products = await get(productsState);
    return products.find((product) => product.id === id);
  })
);

export const cartState = atom<Cart>([]);

export const selectedCartItemIdsState = atom<number[]>([]);

export const cartTotalState = atom((get) => {
  const items = get(cartState);
  return {
    totalItems: items.length,
    totalAmount: items.reduce(
      (total, item) => total + item.product.price * item.quantity,
      0
    ),
  };
});

export const keywordState = atom<string>("");

export const searchResultState = atom(async (get) => {
  const keyword = get(keywordState);
  const products = await get(productsState);
  await new Promise((resolve) => setTimeout(resolve, 1000));
  return products.filter((product) =>
    product.name.toLowerCase().includes(keyword.toLowerCase())
  );
});

export const productsByCategoryState = atomFamily((id: String) =>
  atom(async (get) => {
    await new Promise((resolve) => setTimeout(resolve, 1000));
    const products = await get(productsState);
    return products.filter((product) => String(product.categoryId) === id);
  })
);

export const stationsState = atom(async () => {
  let location: Location | undefined;
  try {
    const { token } = await getLocation({});
    toast(
      "Đã lấy được token chứa thông tin vị trí người dùng. Phía tích hợp cần decode token này ở server. Giả lập vị trí tại VNG Campus...",
      {
        icon: "ℹ",
        duration: 10000,
      }
    );
    await new Promise((resolve) => setTimeout(resolve, 1000));
    location = {
      lat: 10.773756,
      lng: 106.689247,
    };
    // End demo
  } catch (error) {
    console.warn(error);
  }

  const stations = await requestWithFallback<Station[]>("/stations", []);
  const stationsWithDistance = stations.map((station) => ({
    ...station,
    distance: location
      ? formatDistant(
          calculateDistance(
            location.lat,
            location.lng,
            station.location.lat,
            station.location.lng
          )
        )
      : undefined,
  }));

  return stationsWithDistance;
});

export const selectedStationIndexState = atom(0);

export const selectedStationState = atom(async (get) => {
  const index = get(selectedStationIndexState);
  const stations = await get(stationsState);
  return stations[index];
});

export const shippingAddressState = atomWithStorage<
  ShippingAddress | undefined
>(CONFIG.STORAGE_KEYS.SHIPPING_ADDRESS, undefined);

export const ordersState = atomFamily((status:OrderStatus) =>
  atom(async () => {
    // Phía tích hợp thay đổi logic filter server-side nếu cần:
    // const serverSideFilteredData = await requestWithFallback<Order[]>(`/orders?status=${status}`, []);
    const allMockOrders = await requestWithFallback<Order[]>("/orders", []);
    const clientSideFilteredData = allMockOrders.filter(
      (order) => order.status === status
    );
    return clientSideFilteredData;
  })
);

export const deliveryModeState = atomWithStorage<Delivery["type"]>(
  CONFIG.STORAGE_KEYS.DELIVERY,
  "shipping"
);

export const luckyWheelState = atom<LuckyWheelData>({
  rewards: [],
  remainingSpins: 3
});

export const luckyWheelDataState = atom(async (get) => {
  try {
    const response = await axios.get('https://thiepcuoitoandao.id.vn/games/lucky_wheel');
    console.log("Lucky Wheel API Data:", response.data);
    
    if (response.data && Array.isArray(response.data.lucky_wheel)) {
      // Update the writable state
      return {
        rewards: response.data.lucky_wheel.map(item => ({
          id: item.id,
          name: item.prize_name,
          type: 'product',
          value: 0,
          color: '#' + Math.floor(Math.random()*16777215).toString(16)
        })),
        remainingSpins: 0 // Will be updated from user's API
      };
    }
    return { rewards: [], remainingSpins: 0 };
  } catch (error) {
    console.error("Lỗi khi lấy danh sách giải thưởng:", error);
    return { rewards: [], remainingSpins: 0 };
  }
});

export const gameHistoryState = atom<GameActivity[]>([]);

export const checkInState = atom<CheckInStatus>({
  lastCheckIn: null,
  consecutive: 0,
  total: 0
});

export const quizQuestionsState = atom<QuizQuestion[]>([
  {
    id: 1,
    question: "Sản phẩm nào sau đây không phải là sản phẩm của chúng tôi?",
    options: ["Rau sạch", "Thịt bò Wagyu", "Laptop", "Trái cây nhập khẩu"],
    correctAnswer: 2,
    reward: { id: 1, name: "5 điểm", type: "point", value: 5, color: "#4CAF50" }
  },
  // More quiz questions can be added here
]);

// Thêm userState để lưu thông tin user đã xử lý từ API
export const userState = atom<{
  id: string | number;
  name: string;
  avatar?: string;
  points?: number;
  spin_tickets?: number;
}>({
  id: '',
  name: 'Khách',
});

export const activeTabState = atom<string>('tab1');
export const activeQuizState = atom<any>(null);
export const quizzesState = atom<any[]>([]);

// Mission states
export const missionsState = atom<any[]>([
  // Dữ liệu mẫu mặc định
  {
    id: 1,
    name: "Đăng nhập hàng ngày",
    description: "Đăng nhập vào ứng dụng mỗi ngày để nhận thưởng",
    reward_id: null,
    points_reward: 5,
    spin_tickets: 1,
    action_required: "login_daily",
    status: "available"
  }
]);
export const activeMissionState = atom<any>(null);
export const missionFilterState = atom<string>('all'); // 'all', 'available', 'in_progress', 'completed'
