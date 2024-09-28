<!-- src/components/Register.vue -->
<template>
  <div>
    <h1>Register</h1>
    <form @submit.prevent="submitRegister">
      <div v-if="errors">
        <p v-for="error in errors" :key="error">{{ error }}</p>
      </div>
      <div>
        <label for="name">Name:</label>
        <input type="text" v-model="name" required />
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" v-model="email" required />
      </div>
      <div>
        <label for="phone">Phone:</label>
        <input type="text" v-model="phone" required />
      </div>
      <div>
        <label for="address">Address:</label>
        <input type="text" v-model="address" />
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" v-model="password" required />
      </div>
      <div>
        <label for="password_confirmation">Confirm Password:</label>
        <input type="password" v-model="password_confirmation" required />
      </div>
      <button type="submit">Register</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@/stores/authStore.js';

const name = ref('');
const email = ref('');
const phone = ref('');
const address = ref('');
const password = ref('');
const password_confirmation = ref('');
const authStore = useAuthStore();

const submitRegister = async () => {
  await authStore.register({
    name: name.value,
    email: email.value,
    phone: phone.value,
    address: address.value,
    password: password.value,
    password_confirmation: password_confirmation.value,
  });
};
</script>
