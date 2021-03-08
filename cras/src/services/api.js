import axios from 'axios';
import { getToken } from './auth';

const baseUrl = process.env.REACT_APP_API_URL;

const api = axios.create({
  baseURL: baseUrl,
  headers: {
    Accept: 'application/json'
  },
  timeout: 300000
});

api.interceptors.request.use(async (config) => {
  const token = getToken();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

api.interceptors.response.use(
  (response) => {
    if (response.data?.error) {
      return Promise.reject(response);
    }

    return response;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default api;
