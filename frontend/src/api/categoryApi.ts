import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://127.0.0.1:8000/admin',
  headers: {
    'Content-Type': 'application/json',
  },
});

export const getCategories = async () => {
  try {
    const response = await apiClient.get('/categories');
    return response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
    throw error;
  }
};

export const getTopCategories = async () => {
  try {
    const response = await apiClient.get('/top-categories');
    return response.data;
  } catch (error) {
    console.error('Error fetching top categories:', error);
    throw error;
  }
};

// Bạn có thể thêm các phương thức khác như createCategory, updateCategory, deleteCategory, v.v. 