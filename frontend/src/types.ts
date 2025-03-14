export interface Province {
  id: number;
  name: string;
  agent_count: number;
  districts?: District[];
}

export interface District {
  id: number;
  name: string;
  agent_count: number;
  wards?: Ward[];
}

export interface Ward {
  id: number;
  name: string;
  agent_count: number;
}

export interface LocationData {
  provinces: Province[];
}

export interface Agent {
  id: number;
  name: string;
  phone: string;
  email?: string;
  address?: string;
  province_id?: number;
  district_id?: number;
  ward_id?: number;
  latitude?: number;
  longitude?: number;
  province?: {
    id: number;
    name: string;
  };
  district?: {
    id: number;
    name: string;
  };
  ward?: {
    id: number;
    name: string;
  };
  full_address?: string;
} 