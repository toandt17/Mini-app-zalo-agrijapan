export interface UserInfo {
  name: string;
  avatar: string;
  phone: string;
  email: string;
  address: string;
}

export interface Product {
  id: number;
  name: string;
  price: number;
  originalPrice?: number;
  image: string;
  category: Category;
  detail?: string;
  sizes?: Size[];
  colors?: Color[];
}

export interface Category {
  id: number;
  name: string;
  image: string;
}

export interface CartItem {
  product: Product;
  quantity: number;
}

export type Cart = CartItem[];

export interface Location {
  lat: number;
  lng: number;
}

export interface ShippingAddress {
  alias: string;
  address: string;
  name: string;
  phone: string;
}

export interface Station {
  id: number;
  name: string;
  image: string;
  address: string;
  location: Location;
}

export type Delivery =
  | ({
      type: "shipping";
    } & ShippingAddress)
  | ({
      type: "pickup";
    } & Station);

export type OrderStatus = "pending" | "shipping" | "completed";

export interface Order {
  id: number;
  status: OrderStatus;
  createdAt: Date;
  receivedAt: Date;
  items: CartItem[];
  delivery: Delivery;
  total: number;
  note: string;
}

// Game types
export interface Reward {
  id: number;
  name: string;
  type: 'voucher' | 'point' | 'product' | 'none';
  value: number;
  image?: string;
  color: string;
}

export interface LuckyWheelData {
  rewards: Reward[];
  remainingSpins: number;
  lastSpin?: Date;
}

export interface GameActivity {
  id: number;
  type: 'spin' | 'checkin' | 'quiz';
  result: string;
  reward?: Reward;
  createdAt: Date;
}

export interface CheckInStatus {
  lastCheckIn: Date | null;
  consecutive: number;
  total: number;
}

export interface QuizQuestion {
  id: number;
  question: string;
  options: string[];
  correctAnswer: number;
  reward: Reward;
}

// Agent types
export interface Agent {
  id: number;
  name: string;
  address: string;
  phone: string;
  email?: string;
  image?: string;
  location: Location;
  province: string;
  district: string;
  ward: string;
  description?: string;
  openHours?: string;
  distance?: number;
}

export interface LocationData {
  currentLocation: {
    province: string;
    district: string;
    ward: string;
    lat: number;
    lng: number;
  };
  provinces: Province[];
}

export interface Province {
  id: string;
  name: string;
  type: string;
  districts?: District[];
}

export interface District {
  id: string;
  name: string;
  type: string;
  wards?: Ward[];
}

export interface Ward {
  id: string;
  name: string;
  type: string;
}
