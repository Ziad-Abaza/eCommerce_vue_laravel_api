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
            <!-- <div class="options">
              <div class="remember">
                <input id="remember" aria-describedby="remember" type="checkbox" class="checkbox">
                <label for="remember" class="remember-label">Remember me</label>
              </div>
              <a href="#" class="forgot-password">Forgot password?</a>
            </div> -->
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

const authStore = useAuthStore();
const router = useRouter(); 

const form = ref({
  email: '',
  password: ''
});

const handleLogin = async () => {
  try {
    await authStore.handleLogin(form.value);

    router.push('/');
  } catch (error) {
    console.error(error);
  }
}
</script>

<style scoped>
.container {
  background-color: #f9fafb; /* Light gray background */
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
  background-color: #ffffff; /* White background */
  border-radius: 0.5rem;
  box-shadow: 0 0 0.5rem rgba(0, 0, 0, 0.1);
  border: 1px solid #e5e7eb; /* Light gray border */
}

.form-content {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
}

.title {
  font-weight: bold;
  font-size: 1.5rem;
  color: #111827; /* Dark gray text color */
  margin-bottom: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.label {
  display: block;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  color: #111827; /* Dark gray text color */
}

.input {
  width: 100%;
  padding: 0.625rem;
  border: 1px solid #d1d5db; /* Light gray border */
  border-radius: 0.375rem;
  background-color: #f9fafb; /* Light gray background */
  color: #111827; /* Dark gray text color */
  font-size: 0.875rem;
}

.input:focus {
  border-color: #3b82f6; /* Primary blue border on focus */
  outline: none;
}

.options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 1rem;
}

.remember {
  display: flex;
  align-items: center;
}

.checkbox {
  width: 1rem;
  height: 1rem;
  border: 1px solid #d1d5db; /* Light gray border */
  border-radius: 0.25rem;
  background-color: #f9fafb; /* Light gray background */
}

.remember-label {
  margin-left: 0.75rem;
  font-size: 0.875rem;
  color: #6b7280; /* Gray text color */
}

.forgot-password {
  font-size: 0.875rem;
  color: #3b82f6; /* Primary blue text color */
  text-decoration: none;
}

.submit-button {
  width: 100%;
  padding: 0.625rem;
  background-color: #3b82f6; /* Primary blue background */
  color: #ffffff; /* White text color */
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

.submit-button:disabled {
  background-color: #93c5fd; /* Light blue background for disabled state */
  cursor: not-allowed;
}

.spinner {
  position: absolute;
}

.spinner-icon {
  width: 1.25rem;
  height: 1.25rem;
  animation: spin 1s linear infinite;
}

.spinner-circle {
  opacity: 0.25;
}

.spinner-path {
  opacity: 0.75;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>
