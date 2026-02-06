import axios from 'axios';
import { useAuthStore } from './store';
import { useOfflineStore } from './stores/offlineStore';

const api = axios.create({
  baseURL: '/api',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
  },
  timeout: 10000 // 10 second timeout
});

// Add token to requests
api.interceptors.request.use((config) => {
  // Get token from zustand store (which persists to localStorage)
  const token = useAuthStore.getState().token;
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor with offline handling
api.interceptors.response.use(
  (response) => {
    // Cache successful GET responses
    if (response.config.method?.toLowerCase() === 'get' && response.data) {
      const cacheKey = response.config.url;
      useOfflineStore.getState().cacheResponse(cacheKey, response.data, 3600000); // 1 hour TTL
    }
    return response;
  },
  async (error) => {
    const originalRequest = error.config;
    
    // If offline or network error, queue the request
    if (!navigator.onLine || error.code === 'ERR_NETWORK' || error.message === 'Network Error') {
      useOfflineStore.getState().setOfflineStatus(true);
      
      // For POST/PUT requests, queue them for later sync
      if (['post', 'put'].includes(originalRequest?.method?.toLowerCase())) {
        const submissionId = useOfflineStore.getState().addPendingSubmission(
          originalRequest.url.replace('/api', ''),
          originalRequest.data,
          originalRequest.method.toUpperCase()
        );
        
        // Return a promise that resolves with queued status
        return Promise.resolve({
          data: {
            success: true,
            queued: true,
            submissionId,
            message: 'Request queued for sync when online'
          },
          status: 202,
          statusText: 'Accepted',
          headers: {},
          config: originalRequest
        });
      }
      
      // For GET requests, try to return cached data
      if (originalRequest?.method?.toLowerCase() === 'get') {
        const cached = useOfflineStore.getState().getCachedResponse(originalRequest.url);
        if (cached) {
          return Promise.resolve({
            data: cached,
            status: 200,
            statusText: 'OK (Cached)',
            headers: {},
            config: originalRequest,
            fromCache: true
          });
        }
      }
    }
    
    // Handle 401 - token refresh or logout
    if (error.response?.status === 401) {
      useAuthStore.getState().logout();
      if (window.location.pathname !== '/login') {
        window.location.href = '/login';
      }
    }
    
    return Promise.reject(error);
  }
);

// Listen for online/offline events
if (typeof window !== 'undefined') {
  window.addEventListener('online', () => {
    useOfflineStore.getState().setOfflineStatus(false);
    // Auto-sync pending submissions when coming back online
    const pending = useOfflineStore.getState().pendingSubmissions;
    if (pending.length > 0) {
      // Small delay to ensure network is stable
      setTimeout(() => {
        useOfflineStore.getState().syncPendingSubmissions(api);
      }, 1000);
    }
  });
  
  window.addEventListener('offline', () => {
    useOfflineStore.getState().setOfflineStatus(true);
  });
}

export default api;


