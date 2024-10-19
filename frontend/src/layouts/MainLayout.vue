<template>
  <q-layout>
    <!-- Header -->
    <q-header elevated>
      <q-toolbar>
        <q-toolbar-title>
          Order Management System
        </q-toolbar-title>
        <q-btn flat label="Sync Orders" @click="syncOrders" />
      </q-toolbar>
    </q-header>

    <!-- Sidebar (optional) -->
    <q-drawer show-if-above v-model="drawer" bordered>
      <q-list>
        <q-item to="/" exact>
          <q-item-section>Home</q-item-section>
        </q-item>
        <!-- Add more navigation items here -->
      </q-list>
    </q-drawer>

    <!-- Main content -->
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue';
import { useOrderStore } from 'src/stores/order'; // Import your Pinia store

const drawer = ref(false); // For sidebar toggle
const orderStore = useOrderStore(); // Initialize your Pinia store

const syncOrders = async () => {
  await orderStore.syncOrders(); // Call the sync function from the store
};
</script>

<style scoped>
/* Add custom styles here if needed */
</style>
