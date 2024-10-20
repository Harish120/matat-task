<template>
  <q-page class="q-ma-md">
    <q-card flat>
      <q-card-section>
        <!-- Search bar -->
        <div class="row flex justify-end">
          <q-input
            v-model="filter"
            debounce="300"
            placeholder="Search orders..."
            clearable
            square
            @input="fetchOrders"
          />
          <q-btn
            flat
            label="Manual Sync Orders"
            size="sm"
            icon="logout"
            @click="syncOrders"
          />
        </div>
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
import {Notify} from "quasar";


const orderStore = useOrderStore();


const filter = ref('');


const loading = computed(() => orderStore.loading);
const orders = computed(() => orderStore.orders);
const meta = computed(() => orderStore.meta);


const pagination = ref({
  page: 1,
  rowsPerPage: 10,
});


const columns = [
  { name: 'number', label: 'Order Number', align: 'left', field: 'number', sortable: true },
  { name: 'status', label: 'Order Status', align: 'left', field: 'status', sortable: true },
  { name: 'total', label: 'Total', align: 'right', field: 'total', sortable: true },
  { name: 'date_created', label: 'Date Created', align: 'left', field: 'date_created', sortable: true },
  { name: 'line_items_count', label: 'Total Items', align: 'left', field: 'line_items_count', sortable: true },
  { name: 'customer_note', label: 'Customer Note', align: 'left', field: 'customer_note', sortable: false },
];

const syncOrders = async () => {
  loading.value = true;
  await orderStore.syncOrders().then(function() {
    Notify.create({
      message: 'Manual Syncing of order completed! Make sure job is running.',
      timeout:3000,
      color: "green-7"
    });
    loading.value = false;
  });
};

const fetchOrders = () => {
  orderStore.fetchOrders({
    page: pagination.value.page,
    per_page: pagination.value.rowsPerPage,
    query: filter.value,
  });
};


watch(pagination, (newPagination) => {
  fetchOrders();
});

watch(filter, () => {
  pagination.value.page = 1;
  fetchOrders();
});


onMounted(() => {
  fetchOrders();
});
</script>
