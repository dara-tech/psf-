import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter } from 'react-router-dom';
import App from './App';
import '@fontsource/kantumruy-pro/400.css';
import '@fontsource/kantumruy-pro/500.css';
import '@fontsource/kantumruy-pro/600.css';
import '@fontsource/kantumruy-pro/700.css';
import 'leaflet/dist/leaflet.css';
import './index.css';
import { useAuthStore } from './lib/store';
import { useUIStore } from './lib/stores/uiStore';
import api from './lib/api';
import { registerPWA } from './lib/pwa-register';

// Initialize theme before rendering
useUIStore.getState().initTheme();

// Initialize user data from persisted storage
const initializeAuth = async () => {
  const { token, user, permissions, setPermissions, setRoles } = useAuthStore.getState();
  
  // If we have a token but no permissions/roles, fetch them
  if (token && user && (!permissions || permissions.length === 0)) {
    try {
      const response = await api.get('/auth/me');
      if (response.data.permissions) {
        setPermissions(response.data.permissions);
      }
      if (response.data.roles) {
        setRoles(response.data.roles);
      }
    } catch (error) {
      console.error('Failed to initialize auth:', error);
      // If token is invalid, clear auth
      if (error.response?.status === 401) {
        useAuthStore.getState().logout();
      }
    }
  }
};

// Initialize auth before rendering
initializeAuth();

// Register PWA Service Worker
registerPWA();

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <BrowserRouter
      future={{
        v7_startTransition: true,
        v7_relativeSplatPath: true,
      }}
    >
      <App />
    </BrowserRouter>
  </React.StrictMode>
);

