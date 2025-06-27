import axios from 'axios'

const instance = axios.create({
  baseURL: 'http://localhost:8000',
  withCredentials: true,
})

// ✅ Interceptor moet op de INSTANCE
instance.interceptors.request.use(config => {
  const token = document.cookie
    .split('; ')
    .find(row => row.startsWith('XSRF-TOKEN='));
  if (token) {
    config.headers['X-XSRF-TOKEN'] = decodeURIComponent(token.split('=')[1]);
  }
  return config;
})

export default instance;
