<template>
  <q-page class="login-page flex flex-center full-height">
    <q-card class="login-card">
      <q-card-section>
        <div class="text-h6 text-center q-mb-md">Login to Your Account</div>
        <q-form @submit="handleSubmit">
          <!-- Email Input -->
          <q-input
            v-model="email"
            label="Email"
            type="email"
            filled
            dense
            square
            standout
            class="q-mb-md"
            hide-bottom-space
            prepend-icon="mail"
            required
          />

          <!-- Password Input -->
          <q-input
            v-model="password"
            label="Password"
            type="password"
            filled
            dense
            square
            standout
            class="q-mb-lg"
            hide-bottom-space
            prepend-icon="lock"
            required
          />

          <!-- Login Button -->
          <q-btn
            label="Login"
            type="submit"
            color="primary"
            unelevated
            class="full-width q-mb-md"
            size="lg"
          />

          <!-- Link to Registration -->
          <div class="text-center q-mt-sm">
            <q-btn
              flat
              label="Don't have an account? Register"
              color="primary"
              @click="router.push('/register')"
            />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from 'stores/auth';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const authStore = useAuthStore();
const router = useRouter();

const handleSubmit = async () => {
  try {
    await authStore.login({ email: email.value, password: password.value });
    router.push('/'); // Redirect to home after login
  } catch (error) {
    console.error('Login error:', error);
  }
};
</script>

<style scoped>
.login-page {
  display: flex;
  justify-content: center;
  align-items: center;
  height: auto;
}

.login-card {
  width: 400px;
  padding: 24px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.2);
}

.q-mb-md {
  margin-bottom: 16px;
}

.q-mb-lg {
  margin-bottom: 24px;
}

.q-btn {
  border-radius: 8px;
}

.full-height {
  height: 100%;
}

.full-width {
  width: 100%;
}
</style>
