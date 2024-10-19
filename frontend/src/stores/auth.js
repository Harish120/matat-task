import { defineStore } from 'pinia';
import { axiosInstance } from 'boot/axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('token') || null, // Load token from localStorage
    user: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token, // Check if the user is logged in
    getUser: (state) => state.user,
  },

  actions: {
    async login(credentials) {
      const response = await axiosInstance.post('/login', credentials);
      this.setAuth(response.data);
    },

    async register(data) {
      const response = await axiosInstance.post('/register', data);
      this.setAuth(response.data);
    },

    setAuth(data) {
      this.$state.token = data.access_token;
      this.$state.user = data.user;

      // Store token in localStorage
      localStorage.setItem('token', this.token);

      // Set axios Authorization header
      axiosInstance.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
    },

    async fetchUser() {
      if (!this.token) return;

      try {
        const token = localStorage.getItem('token')
        axiosInstance.defaults.headers.common['Authorization'] = 'Bearer ' + token
        const response = await axiosInstance.get('/user');
        this.$state.user = response.data;
      } catch (error) {
        this.logout(); // If token is invalid or expired, log out
      }
    },

    logout() {
      this.token = null;
      this.user = null;

      localStorage.removeItem('token');
      delete axiosInstance.defaults.headers.common['Authorization'];
    },
  },
});
