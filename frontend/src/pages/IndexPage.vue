<template>
  <q-page class="q-ma-md">
    <q-card flat>
      <q-card-section>
        <div class="row flex justify-end">
          <q-select
            v-model="orderStatus"
            :options="orderStatusOptions"
            bordered
            clearable
            dense
            emit-value
            map-options
            class="q-pr-sm"
            outlined
            square
            label="Filter Status"
            style="width: 250px;"
            @update:model-value="fetchOrders(1, pagination.rowsPerPage)"
          >
          </q-select>
          <q-input
            v-model="query"
            debounce="300"
            placeholder="Search orders..."
            clearable
            bordered
            dense
            outlined
            square
            @input="fetchOrders(1, pagination.rowsPerPage)"
          />
          <q-btn
            flat
            label="Manual Sync Orders"
            size="sm"
            icon="logout"
            @click="syncOrders"
          />
        </div>

        <template v-if="orders.length > 0">
          <q-table
            class="q-mt-md"
            :rows="orders"
            :columns="columns"
            row-key="id"
            :loading="loading"
            v-model:pagination="pagination"
          >
            <template v-slot:body-cell-line_items="props">
              <q-expansion-item icon="list" label="View Items">
                <div v-for="item in props.row.line_items" :key="item.id">
                  {{ item.name }} - Quantity: {{ item.quantity }}
                </div>
              </q-expansion-item>
            </template>
<!--          <template v-slot:pagination v-if="meta">-->
<!--            <q-btn-->
<!--              icon="first_page"-->
<!--              color="grey-8"-->
<!--              round-->
<!--              dense-->
<!--              flat-->
<!--              :disable="pagination.page === 1"-->
<!--              @click="goToPage(1)"-->
<!--            />-->
<!--            <q-btn-->
<!--              icon="chevron_left"-->
<!--              color="grey-8"-->
<!--              round-->
<!--              dense-->
<!--              flat-->
<!--              :disable="pagination.page === 1"-->
<!--              @click="goToPage(pagination.page - 1)"-->
<!--            />-->
<!--            <span class="q-mx-md">{{ pagination.page }} / {{ meta.last_page }}</span>-->
<!--            <q-btn-->
<!--              icon="chevron_right"-->
<!--              color="grey-8"-->
<!--              round-->
<!--              dense-->
<!--              flat-->
<!--              :disable="pagination.page === meta.last_page"-->
<!--              @click="goToPage(pagination.page + 1)"-->
<!--            />-->
<!--            <q-btn-->
<!--              icon="last_page"-->
<!--              color="grey-8"-->
<!--              round-->
<!--              dense-->
<!--              flat-->
<!--              :disable="pagination.page === meta.last_page"-->
<!--              @click="goToPage(meta.last_page)"-->
<!--            />-->
<!--          </template>-->
          </q-table>
        </template>
        <template v-else>
          <q-card-section>
            <p>No records available.</p>
          </q-card-section>
        </template>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useOrderStore } from 'src/stores/order';
import { Notify } from 'quasar';

// Order store and refs
const orderStore = useOrderStore();
const orderStatus = ref(null);
const query = ref('');
const filter = ref({});

const loading = computed(() => orderStore.loading);
const orders = computed(() => orderStore.orders);
const meta = computed(() => orderStore.meta);
const pagination = ref({
  page: 1,
  rowsPerPage: 10,
});
const orderStatusOptions = ref(["completed", "processing", "pending", "refunded"])

// Columns for the table
const columns = [
  { name: 'number', label: 'Order Number', align: 'left', field: 'number', sortable: true },
  { name: 'status', label: 'Order Status', align: 'left', field: 'status', sortable: true },
  { name: 'total', label: 'Total', align: 'right', field: 'total', sortable: true },
  { name: 'date_created', label: 'Date Created', align: 'left', field: 'date_created', sortable: true },
  { name: 'line_items', label: 'Line Items', align: 'left', field: 'line_items', sortable: false },
  { name: 'line_items_count', label: 'Total Items', align: 'left', field: 'line_items_count', sortable: true },
  { name: 'customer_note', label: 'Customer Note', align: 'left', field: 'customer_note', sortable: false },
  { name: 'billing', label: 'Billing Address', align: 'left', field: 'billing', sortable: false },
  { name: 'shipping', label: 'Shipping Address', align: 'left', field: 'shipping', sortable: false },
];

// Fetch orders based on pagination and query
const fetchOrders = async (page = pagination.value.page, perPage = pagination.value.rowsPerPage) => {

  const filtersParam = orderStatus.value ? ({
    status: orderStatus.value
  }): null;
  const params = {
    page: page,
    per_page: perPage,
    query: query.value,
    filter: JSON.stringify(filtersParam)
  }
  await orderStore.fetchOrders(params);
};

// Navigate to a specific page
const goToPage = async (page) => {
  if (page >= 1 && page <= meta.value.last_page) {
    pagination.value.page = page; // Update current page
    await fetchOrders(page, pagination.value.rowsPerPage);

  }
};

const syncOrders = async () => {
  await orderStore.syncOrders();
};

// Watch for query changes and reset to page 1
watch(query, () => {
  pagination.value.page = 1;
  fetchOrders(1, pagination.value.rowsPerPage);
});

// Watch for filter changes and reset to page 1
watch(filter, () => {
  pagination.value.page = 1;
  fetchOrders(1, pagination.value.rowsPerPage);
});

// Watch for changes in rows per page
watch(() => pagination.value.rowsPerPage, (newVal) => {
  pagination.value.page = 1; // Reset to page 1 when rows per page changes
  fetchOrders(1, newVal);
});

// Initial data fetch
onMounted(() => {
  fetchOrders();
});
</script>
