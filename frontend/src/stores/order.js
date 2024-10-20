import { defineStore } from 'pinia';
import { axiosInstance } from 'boot/axios';

export const useOrderStore = defineStore('order', {
  state: () => ({
    orders: [],
    loading: false,
    meta: {},
  }),
  actions: {
    async fetchOrders(params = {}) {
      this.loading = true;
      try {
        const response = await axiosInstance.get('/orders', { params });
        this.orders = response.data.data;
        this.meta = response.data.meta;
      } catch (error) {
        console.error('Error fetching orders:', error);
      } finally {
        this.loading = false;
      }
    },

    async syncOrders() {
      try {
        await axiosInstance.post('/orders/sync');
        await this.fetchOrders();
      } catch (error) {
        console.error('Error syncing orders:', error);
      }
    },
  },
});
