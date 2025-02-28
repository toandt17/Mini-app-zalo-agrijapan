import axios from 'axios';

const apiUrl = window.APP_CONFIG.template.apiUrl;

export const fetchProducts = async () => {
  try {
    const response = await axios.get(`${apiUrl}`);
    return response.data;
  } catch (error) {
    console.error("Lỗi khi lấy sản phẩm:", error);
    throw error;
  }
};