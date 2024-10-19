<template>
  <router-view />
</template>

<script setup>
import { useAuthStore } from 'stores/auth';

defineOptions({
  name: 'App'
});

const authStore = useAuthStore();

if (authStore.token) {
  authStore.fetchUser(); // Auto-fetch user on app reload if token exists
}
</script>
