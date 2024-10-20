<template>
  <q-layout view="hHh Lpr fFf">
    <!-- Header -->
    <q-header elevated class="bg-primary text-white">
      <q-toolbar>
        <q-toolbar-title>
          Order Management System
        </q-toolbar-title>
        <q-btn flat label="Logout" size="sm" icon="logout" @click="handleLogout" />
      </q-toolbar>
    </q-header>

    <!-- Sidebar (optional) -->
    <q-drawer show-if-above v-model="drawer" bordered class="bg-grey-2">
      <q-list class="q-mt-md">
        <q-item to="/" exact>
          <q-item-label class="q-mt-sm"><q-icon name="home" />&nbsp;Order List</q-item-label>
        </q-item>
      </q-list>
    </q-drawer>

    <!-- Main content -->
    <q-page-container class="q-page-container">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue';
import { useOrderStore } from 'stores/order';
import {useAuthStore} from "stores/auth";

const drawer = ref(false);
const authStore = useAuthStore();

const handleLogout = async () => {
  await authStore.logout();
  window.location.reload();
};
</script>

<style scoped>
.q-page-container {
  overflow: auto; /* Allow scrolling only in the page container */
}

/* Optional: Add additional styling for your layout */
</style>
