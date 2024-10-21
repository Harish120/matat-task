import { defineStore } from 'pinia';
import { axiosInstance } from 'boot/axios';
import { ref } from 'vue';

export const useOrderStore = defineStore('order', () => {

  const orders = ref({});
  const loading = ref(false);
  const meta = ref({});


  const fetchOrders = async (params = {}) => {
    loading.value = true;
    try {
        await axiosInstance.get('/orders', { params })
          .then(function(response) {
            orders.value = response.data.data;
            meta.value = response.data.meta;
        })
    } catch (error) {
      console.error('Error fetching orders:', error);
    } finally {
      loading.value = false;
    }
  };

  const syncOrders = async () => {
    try {
      await axiosInstance.post('/orders/sync');
      await fetchOrders();
    } catch (error) {
      console.error('Error syncing orders:', error);
    }
  };

  return {
    orders,
    loading,
    meta,
    fetchOrders,
    syncOrders,
  };
});
