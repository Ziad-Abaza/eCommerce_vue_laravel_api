<template>
  <section class="container">
    <div class="form-container">
      <div class="form-box">
        <div class="form-content">
          <h1 class="title">Sign in to your account</h1>
          <form @submit.prevent="handleLogin(form)">
            <div class="form-group">
              <label for="email" class="label">Your email</label>
              <input v-model="form.email" type="email" name="email" id="email" class="input" placeholder="email@batu.edu.eg">
            </div>
            <div class="form-group">
              <label for="password" class="label">Password</label>
              <input v-model="form.password" type="password" name="password" id="password" placeholder="••••••••" class="input">
            </div>
            <div v-if="errorMessage" class="error-message">
              {{ errorMessage }}
            </div>
            <button type="submit" class="submit-button">
              <span>Sign in</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/authStore';
import { useRouter } from 'vue-router'; 
import { useNotificationStore } from "@/stores/notificationStore";

const notificationStore = useNotificationStore();
const authStore = useAuthStore();
const router = useRouter(); 

const form = ref({
  email: '',
  password: ''
});

const errorMessage = ref('');

const handleLogin = async () => {
  errorMessage.value = '';
  try {
    const success = await authStore.handleLogin(form.value);
    if (success) {
      notificationStore.setNotifications(
        "Welcome back!",
        "success"
      );
      router.push('/');
    } else {
      errorMessage.value = "Login failed. Please check your credentials.";
    }
  } catch (error) {
    errorMessage.value = "An error occurred. Please try again later.";
    console.error(error);
  }
}
</script>

<style scoped>
.container {
  background-color: #f9fafb;
  width: 100%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 1.5rem;
  width: 100%;
}

.form-box {
  width: 100%;
  max-width: 24rem;
  background-color: #ffffff;
  border-radius: 0.5rem;
  box-shadow: 0 0 0.5rem rgba(0, 0, 0, 0.1);
  border: 1px solid #e5e7eb;
}

.form-content {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
}

.title {
  font-weight: bold;
  font-size: 1.5rem;
  color: #111827;
  margin-bottom: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.label {
  display: block;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  color: #111827;
}

.input {
  width: 100%;
  padding: 0.625rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  background-color: #f9fafb;
  color: #111827;
  font-size: 0.875rem;
}

.input:focus {
  border-color: #3b82f6;
  outline: none;
}

.error-message {
  margin-bottom: 1rem;
  color: #f87171;
  font-size: 0.875rem;
  background-color: #fee2e2;
  padding: 0.5rem;
  border-radius: 0.375rem;
}

.submit-button {
  width: 100%;
  padding: 0.625rem;
  background-color: #3b82f6;
  color: #ffffff;
  font-size: 0.875rem;
  font-weight: 500;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
