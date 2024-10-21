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
<!--:sort-method="handleRequest"-->
        <template v-if="orderList.length > 0">
          <q-table
            flat bordered
            class="q-mt-md"
            ref="tableRef"
            :rows="orderList"
            :columns="columns"
            row-key="number"
            :loading="loading"
            v-model:pagination="pagination"
            binary-state-sort
            @request="onRequest"
          >
            <template v-slot:body-cell-serial_number="props">
                <q-td>
                  {{ orderList.indexOf(props.row) +
                      1 +
                        pagination.rowsPerPage * (pagination.page - 1)
                    }}
                </q-td>
            </template>
            <template v-slot:body-cell-line_items="props">
              <q-expansion-item icon="list" label="View Items">
                <div v-for="item in props.row.line_items" :key="item.id">
                  {{ item.name }} - Quantity: {{ item.quantity }}
                </div>
              </q-expansion-item>
            </template>
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
import {axiosInstance} from "boot/axios";

// Order store and refs
const orderStore = useOrderStore();
const orderStatus = ref(null);
const query = ref('');
const filter = ref({});

const loading = computed(() => orderStore.loading);
const orderList = ref([]);
const meta = ref([]);
const pagination = ref({
  sortBy: 'date_created',
  descending: true,
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 10
})
const orderStatusOptions = ref(["completed", "processing", "pending", "refunded"])

// Columns for the table
const columns = [
  { name: 'serial_number', label: 'S.N.', align: 'left', field: 'serial_number', sortable: false },
  { name: 'number', label: 'Order Number', align: 'left', field: 'number', sortable: true },
  { name: 'status', label: 'Order Status', align: 'left', field: 'status', sortable: false },
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
    filter: JSON.stringify(filtersParam),
    sort: pagination.value.sortBy,       // Add sort field
    direction: pagination.value.descending ? 'desc' : 'asc', // Add sort direction
  }
  const result = await orderStore.fetchOrders(params);
  orderList.value = result.data
  meta.value = result.meta

  pagination.value.page = result.meta.current_page
  pagination.value.rowsPerPage = result.meta.per_page
  pagination.value.rowsNumber = result.meta.total
};

const onRequest = (props) => {
  const { page, rowsPerPage, sortBy, descending } = props.pagination
  pagination.value.sortBy = sortBy;
  pagination.value.descending = descending;

  // emulate server
  setTimeout(async () => {
    const fetchCount = rowsPerPage === 0 ? pagination.value.rowsNumber : rowsPerPage
    // fetch data from "server"
    await fetchOrders(page, fetchCount)
  }, 100)
}

const syncOrders = async () => {
  await orderStore.syncOrders().then(function() {
    Notify.create({
      message: "Manual syncing of orders done. Please run job on backend!",
      color: "green-7",
      timeout: 600
    })
  })
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
