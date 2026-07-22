import axios from 'axios';
import { useAuthStore } from '@/stores/authStore';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || '/api/v1',
  timeout: 30000,
  headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
});

api.interceptors.request.use((config) => {
  const { token, tenant } = useAuthStore.getState();
  if (token) config.headers.Authorization = `Bearer ${token}`;
  if (tenant) config.headers['X-Tenant-ID'] = tenant.id;
  config.headers['X-Request-ID'] = crypto.randomUUID();
  return config;
});

api.interceptors.response.use(
  (r) => r.data,
  async (error) => {
    if (error.response?.status === 401 && !error.config._retry) {
      error.config._retry = true;
      try {
        await useAuthStore.getState().refreshTokenFn();
        error.config.headers.Authorization = `Bearer ${useAuthStore.getState().token}`;
        return api(error.config);
      } catch {
        useAuthStore.getState().logout();
        window.location.href = '/login';
      }
    }
    return Promise.reject(error);
  }
);

export const authApi = {
  login: (email: string, password: string): Promise<any> => api.post('/auth/login', { email, password }),
  logout: (): Promise<any> => api.post('/auth/logout'),
  refreshToken: (token: string): Promise<any> => api.post('/auth/refresh', { refresh_token: token }),
  getUser: (): Promise<any> => api.get('/auth/user'),
};

export const attendanceApi = {
  getDashboardStats: (tenantId: number | string, range: string): Promise<any> => api.get(`/${tenantId}/dashboard/stats?range=${range}`),
  getAttendanceTrends: (tenantId: number | string, range: string): Promise<any> => api.get(`/${tenantId}/dashboard/trends?range=${range}`),
  checkIn: (data: any): Promise<any> => api.post('/attendance/check-in', data),
  checkOut: (data: any): Promise<any> => api.post('/attendance/check-out', data),
  getTodaySummary: (): Promise<any> => api.get('/attendance/today-summary'),
};

export const paymentApi = {
  createRazorpayOrder: (data: any): Promise<any> => api.post('/payment/razorpay/create-order', data),
  verifyRazorpayPayment: (data: any): Promise<any> => api.post('/payment/razorpay/verify', data),
  applyCoupon: (code: string, plan: string, billing: string): Promise<any> => api.post('/payment/apply-coupon', { coupon_code: code, plan, billing }),
  getBillingHistory: (): Promise<any> => api.get('/payment/billing-history'),
};

export const faceRecognitionApi = {
  verifyFace: (data: any): Promise<any> => api.post('/face-recognition/verify', data),
  registerFace: (data: FormData): Promise<any> => api.post('/face-recognition/register', data, { headers: { 'Content-Type': 'multipart/form-data' } }),
};

export const deviceApi = {
  getDevices: (params?: any): Promise<any> => api.get('/devices', { params }),
  syncAttendance: (id: number): Promise<any> => api.post(`/devices/${id}/sync`),
  discover: (subnet: string): Promise<any> => api.post('/devices/discover', { subnet }),
};

export default api;
