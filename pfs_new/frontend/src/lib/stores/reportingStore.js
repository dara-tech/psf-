import { create } from 'zustand';
import api from '../api';

export const useReportingStore = create((set, get) => ({
  tableData: [],
  dashboardData: null,
  sites: [],
  loading: false,
  error: null,

  fetchTable: async (locale = 'en') => {
    set({ loading: true, error: null });
    try {
      // Pass locale as query parameter for GET request
      const response = await api.get(`/reporting/table?locale=${locale}`);
      if (response.data.success) {
        set({ 
          tableData: response.data.data || [],
          sites: response.data.sites || [],
          loading: false 
        });
      }
    } catch (error) {
      console.error('Fetch table error:', error);
      set({ error: error.message, loading: false });
    }
  },

  fetchDashboard: async (filters = {}) => {
    set({ loading: true, error: null });
    try {
      const response = await api.post('/reporting/dashboard', filters);
      if (response.data.success) {
        set({ 
          dashboardData: response.data.data,
          sites: response.data.sites || [],
          loading: false 
        });
      }
    } catch (error) {
      console.error('Fetch dashboard error:', error);
      set({ error: error.message, loading: false });
    }
  },
}));
