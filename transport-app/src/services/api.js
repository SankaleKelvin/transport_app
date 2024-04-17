import axios from 'axios';
import router from '../router/router';
import TokenService from './token.service';

const instance = axios.create({
    baseURL: "http://127.0.0.1:8000/api/",
    headers: {
        "Content-Type": "application/json",
    },
});


instance.interceptors.request.use(
    (config) => {
      const token = TokenService.getToken();
      if (token) {
        config.headers["Authorization"] = 'Bearer ' + token; 
      }
      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );

  
// Interceptor to handle global authentication errors
instance.interceptors.response.use(
  (response) => {
      return response;
  },
  (error) => {
      if (error.response && error.response.status === 401) {
          TokenService.removeToken();
          router.push({ name: 'Home' });
      }
      return Promise.reject(error);
  }
);


  export default instance;