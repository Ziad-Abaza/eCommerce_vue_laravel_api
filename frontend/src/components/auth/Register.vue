<template>
  <section class="container">
    <div class="form-container">
      <div class="form-box">
        <div class="form-content">
          <h1 class="title">Create a new account</h1>
          <form @submit.prevent="submitRegister">
            <div v-if="errors.length" class="error-message">
              <p v-for="(error, index) in errors" :key="index">{{ error }}</p>
            </div>
            <div class="form-group">
              <label for="name" class="label">Name</label>
              <input type="text" v-model="name" id="name" class="input" placeholder="John Doe" required />
            </div>
            <div class="form-group">
              <label for="email" class="label">Email</label>
              <input type="email" v-model="email" id="email" class="input" placeholder="email@example.com" required />
            </div>
            <div class="form-group">
              <label for="phone" class="label">Phone</label>
              <input type="text" v-model="phone" id="phone" class="input" placeholder="123-456-7890" />
            </div>
            <div class="form-group">
              <label for="address" class="label">Address</label>
              <input type="text" v-model="address" id="address" class="input" placeholder="123 Main St, City, Country" />
            </div>
            <div class="form-group">
              <label for="password" class="label">Password</label>
              <input type="password" v-model="password" id="password" class="input" placeholder="••••••••" required />
            </div>
            <div class="form-group">
              <label for="password_confirmation" class="label">Confirm Password</label>
              <input type="password" v-model="password_confirmation" id="password_confirmation" class="input" placeholder="••••••••" required />
            </div>
            <button type="submit" class="submit-button">
              <span>Register</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore.js';
import { useNotificationStore } from '@/stores/notificationStore.js';

const name = ref('');
const email = ref('');
const phone = ref('');
const address = ref('');
const password = ref('');
const password_confirmation = ref('');
const authStore = useAuthStore();
const errors = ref([]);
const router = useRouter();
const notificationStore = useNotificationStore();

const submitRegister = async () => {
  errors.value = [];
  const success = await authStore.register({
    name: name.value,
    email: email.value,
    phone: phone.value,
    address: address.value,
    password: password.value,
    password_confirmation: password_confirmation.value,
  });

  if (success) {
    notificationStore.setNotifications('Registration successful', 'success');
    notificationStore.setNotifications('Welcome  to our website!');
    router.push('/'); 
  } else {
    errors.value = [authStore.getError];
  }
};
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
