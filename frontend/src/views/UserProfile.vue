<template>
  <HeaderSection />
  <div class="container">
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
      <p>Loading, please wait...</p>
    </div>
    <div v-else>
      <div v-if="isLoggedIn" class="user-data-section">
        <personal-info :data="userData" />
        <order-details :data="orderData" />
        <purchase-details :data="purchaseData" />
      </div>
      <div v-else class="login-prompt">
        <h2>Welcome!</h2>
        <p>Please login to view your personal information and orders.</p>
        <button @click="goToLoginPage">Login Now</button>
      </div>
    </div>
  </div>
  <div class="favorite-container">
    <favorite-products :data="favoriteData" />
  </div>
  <FooterNavigation />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import FooterNavigation from '@/components/layouts/FooterNavigation.vue';
import HeaderSection from '@/components/layouts/HeaderSection.vue';
import orderDetails from '@/components/profile/orderDetails.vue';
import purchaseDetails from '@/components/profile/purchaseDetails.vue';
import personalInfo from '@/components/profile/personalInfo.vue';
import favoriteProducts from '@/components/favoriteProducts.vue';
import { useProductsStore } from '@/stores/productsStore';
import { useUserStore } from '@/stores/userStore';
import { useAuthStore } from '@/stores/authStore';

const router = useRouter();
const productsStore = useProductsStore();
const userAuth = useAuthStore();
const userStore = useUserStore();

const isLoggedIn = computed(() => userAuth.isLoggedIn);
const loading = computed(() => productsStore.loading);
const userData = computed(() => userStore.getUserData);
const orderData = computed(() => userStore.getOrderData);
const purchaseData = computed(() => userStore.getPurchaseData);
const favoriteData = computed(() => userStore.getUserFavorite);

const goToLoginPage = () => {
  router.push('/login');
};

onMounted(() => {
  if (isLoggedIn.value) {
    userStore.fetchUsers();
  }
});
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Roboto', sans-serif;
}

.container {
  width: 90%;
  max-width: 1200px;
  margin: 40px auto;
  background-color: #f9fafb;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.loading-container {
  text-align: center;
  padding: 50px;
}

.spinner {
  border: 6px solid #f3f3f3;
  border-radius: 50%;
  border-top: 6px solid #3498db;
  width: 40px;
  height: 40px;
  animation: spin 2s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.user-data-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.favorite-container {
  margin: 2rem auto 4rem;
}

.card {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin-bottom: 20px;
  transition: transform 0.2s;
}

.card:hover {
  transform: translateY(-5px);
}

.card-title {
  font-weight: bold;
  margin-bottom: 10px;
}

.card-content {
  font-size: 14px;
  color: #555;
}

@media screen and (max-width: 768px) {
  .user-data-section {
    grid-template-columns: 1fr;
  }

  .favorite-container {
    width: 100%;
    overflow-x: auto;
  }

  .favorite-products::-webkit-scrollbar {
    display: none; 
  }
}

.login-prompt {
  text-align: center;
}

.login-prompt h2 {
  font-size: 24px;
  margin-bottom: 10px;
}

.login-prompt button {
  background-color: #3498db;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.login-prompt button:hover {
  background-color: #2980b9;
}
</style>
