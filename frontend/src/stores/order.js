import { defineStore } from 'pinia';
import { axiosInstance } from 'boot/axios';
import { ref } from 'vue';

export const useOrderStore = defineStore('order', () => {
  const orders = ref([]);
  const loading = ref(false);
  const meta = ref({});

  const fetchOrders = async (params = {}) => {
    loading.value = true;
    return new Promise(async (resolve, reject) => {
      try {
        const response = await axiosInstance.get('/orders', { params });
        orders.value = response.data.data;
        meta.value = response.data.meta;
        resolve(response.data); // Resolve the promise with the response data
      } catch (error) {
        console.error('Error fetching orders:', error);
        reject(error); // Reject the promise with the error
      } finally {
        loading.value = false;
      }
    });
  };

  const syncOrders = async () => {
    return new Promise(async (resolve, reject) => {
      try {
        await axiosInstance.post('/orders/sync');
        await fetchOrders();
        resolve(); // Resolve the promise when sync is complete
      } catch (error) {
        console.error('Error syncing orders:', error);
        reject(error); // Reject the promise with the error
      }
    });
  };

  return {
    orders,
    loading,
    meta,
    fetchOrders,
    syncOrders,
  };
});
