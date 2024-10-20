<template>
  <q-page class="q-ma-md">
    <q-card flat>
      <q-card-section>
        <!-- Search bar -->
        <q-input
          v-model="filter"
          debounce="300"
          placeholder="Search orders..."
          clearable
          square
          @input="fetchOrders"
        />

        <!-- Table with pagination -->
        <q-table
          class="q-mt-md"
          :rows="orders"
          :columns="columns"
          row-key="id"
          :loading="loading"
          v-model:pagination="pagination"
          @request="fetchOrders"
        />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import {ref, computed, onMounted, watch} from 'vue';
import {useOrderStore} from 'src/stores/order';

// Initialize store
const orderStore = useOrderStore();

// Local states
const filter = ref('');

// Computed properties from store
const loading = computed(() => orderStore.loading);
const orders = computed(() => orderStore.orders);
const meta = computed(() => orderStore.meta); // Meta for pagination

// Pagination object for q-table
const pagination = ref({
  page: 1,         // Default page
  rowsPerPage: 10, // Default rows per page
});

// Table columns definition
const columns = [
  { name: 'number', label: 'Order Number', align: 'left', field: 'number', sortable: true },
  { name: 'status', label: 'Order Status', align: 'left', field: 'status', sortable: true },
  { name: 'total', label: 'Total', align: 'right', field: 'total', sortable: true },
  { name: 'date_created', label: 'Date Created', align: 'left', field: 'date_created', sortable: true },
  { name: 'line_items_count', label: 'Total Items', align: 'left', field: 'line_items_count', sortable: true },
  { name: 'customer_note', label: 'Customer Note', align: 'left', field: 'customer_note', sortable: false },
];

// Fetch orders function
const fetchOrders = () => {
  orderStore.fetchOrders({
    page: pagination.value.page,        // Current page
    per_page: pagination.value.rowsPerPage, // Rows per page
    query: filter.value,                // Pass the filter
  });
};

// Watch for pagination changes to fetch new data
watch(pagination, (newPagination) => {
  fetchOrders(); // Fetch orders whenever pagination changes
});

watch(filter, () => {
  pagination.value.page = 1; // Reset to the first page when filter changes
  fetchOrders(); // Fetch orders whenever the filter changes
});

// Initial fetch on component mount
onMounted(() => {
  fetchOrders();
});
</script>
