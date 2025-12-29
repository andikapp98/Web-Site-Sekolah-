import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

export const login = async (email, password) => {
  try {
    const response = await axios.post(`${API_URL}/login`, {
      email,
      password
    });
    
    if (response.data.token) {
      localStorage.setItem('authToken', response.data.token);
      localStorage.setItem('user', JSON.stringify(response.data.user));
    }
    
    return response.data;
  } catch (error) {
    if (error.response && error.response.data) {
      // Handle Laravel validation errors
      if (error.response.data.errors) {
        const firstError = Object.values(error.response.data.errors)[0];
        throw new Error(firstError[0]);
      }
      throw new Error(error.response.data.message || 'Login gagal');
    }
    throw new Error('Tidak dapat terhubung ke server. Periksa koneksi Anda.');
  }
};

export const logout = async () => {
  const token = localStorage.getItem('authToken');
  if (token) {
    try {
      await axios.post(`${API_URL}/logout`, {}, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });
    } catch (error) {
      console.error('Logout failed on server, clearing client session anyway.', error);
    }
  }
  localStorage.removeItem('authToken');
  localStorage.removeItem('user');
};

export const isAuthenticated = () => {
  return localStorage.getItem('authToken') !== null;
};

export const getUser = () => {
  const user = localStorage.getItem('user');
  return user ? JSON.parse(user) : null;
};