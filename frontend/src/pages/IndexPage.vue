<template>
  <q-page class="q-ma-md">
    <q-card class="my-card" flat>
      <q-card-section class="q-pa-none">
        <div id="printMe" class="col-12">
          <q-spinner v-if="loading" />

          <q-table
            v-if="!loading && orders.length"
            :rows="orders"
            :columns="columns"
            row-key="id"
            :pagination="pagination"
            @request="onRequest"
          >
            <template v-slot:top-right>
              <q-input v-model="filter" debounce="300" placeholder="Search..." />
            </template>
          </q-table>

          <q-banner v-if="!orders.length && !loading">No orders found.</q-banner>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useOrderStore } from 'src/stores/order';

const orderStore = useOrderStore();

const loading = computed(() => orderStore.loading);
const orders = computed(() => orderStore.orders);
const filter = ref('');

const columns = [
  { name: 'number', label: 'Order Number', align: 'left', field: 'number' },
  { name: 'status', label: 'Order Status', align: 'left', field: 'status' },
  { name: 'total', label: 'Total', align: 'right', field: 'total' },
  { name: 'date_created', label: 'Date Created', align: 'left', field: 'date_created' },
  { name: 'line_items_count', label: 'Total Items', align: 'left', field: 'line_items_count' },
  { name: 'customer_note', label: 'Total Items', align: 'left', field: 'customer_note' },
];

const pagination = ref({ page: 1, rowsPerPage: 10 });

const onRequest = ({ pagination, filter }) => {
  orderStore.fetchOrders({
    page: pagination.page,
    per_page: pagination.rowsPerPage,
    filter: filter,
  });
};

onMounted(() => {
  orderStore.fetchOrders();
});
</script>

<style scoped>
/* Add custom styles here if needed */
</style>
