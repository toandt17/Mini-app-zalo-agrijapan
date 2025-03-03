import axios from 'axios';
import { Product } from '@/types';

const apiClient = axios.create({
    baseURL: 'https://test.vieclamphuquoc.com.vn/admin',
    headers: {
    'Content-Type': 'application/json',
  },
});

export const getProducts = async (): Promise<Product[]> => {
  try {
    const response = await apiClient.get('/products');
    return response.data;
  } catch (error) {
    console.error('Error fetching products:', error);
    throw error;
  }
};

export const getProductsByCategory = async (categoryId: number) => {
  try {
    const response = await apiClient.get(`/products/category/${categoryId}`);
    return response.data;
  } catch (error) {
    console.error('Error fetching products by category:', error);
    throw error;
  }
};

export const searchProducts = async (keyword: string) => {
  try {
    const response = await apiClient.get('/products/search', {
      params: { keyword }
    });
    console.log('Search keyword:', keyword);
    console.log('Search result:', response.data);
    return response.data;
  } catch (error) {
    console.error('Error searching products:', error);
    throw error;
  }
};

// Bạn có thể thêm các phương thức khác như getProductById, createProduct, updateProduct, deleteProduct, v.v. 