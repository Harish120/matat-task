<template>
  <q-page class="register-page flex flex-center full-height">
    <q-card class="register-card">
      <q-card-section>
        <div class="text-h6 text-center q-mb-md">Create a New Account</div>
        <q-form @submit="handleSubmit">
          <!-- Name Input -->
          <q-input
            v-model="name"
            label="Name"
            filled
            dense
            square
            standout
            class="q-mb-md"
            hide-bottom-space
            prepend-icon="person"
            required
          />

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

          <!-- Register Button -->
          <q-btn
            label="Register"
            type="submit"
            color="primary"
            unelevated
            class="full-width q-mb-md"
            size="lg"
          />

          <!-- Link to Login -->
          <div class="text-center q-mt-sm">
            <q-btn
              flat
              label="Already have an account? Login"
              color="primary"
              @click="router.push('/login')"
            />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import {ref} from 'vue';
import {useAuthStore} from 'stores/auth';
import {useRouter} from 'vue-router';

const name = ref('');
const email = ref('');
const password = ref('');
const authStore = useAuthStore();
const router = useRouter();

const handleSubmit = async () => {
  try {
    await authStore.register({name: name.value, email: email.value, password: password.value});
    router.push('/'); // Redirect to home after successful registration
  } catch (error) {
    console.error('Registration error:', error);
  }
};
</script>

<style scoped>
.register-page {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.register-card {
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
