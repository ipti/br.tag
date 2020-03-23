import axios from 'axios';
import config  from '../constants/AppConfig';

const  http = axios.create({
   baseURL: config.baseUrlApi,
   headers: {
         'Content-Type': 'application/json'
   },
   timeout: 3000000
});

http.interceptors.request.use(
   (config) => {
      const token = localStorage.getItem('token') || null;
      if(token !== null){
         config.headers['Authorization'] = `Bearer ${ token }`;
      }
      return config;
      
   }, function (error) {
      return Promise.reject(error);
});

export default http;
  