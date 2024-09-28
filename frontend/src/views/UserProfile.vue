<template>
  <HeaderSection />
  <div class="main-container">
    <button @click="logout"  class="logout-button">Logout</button>
    <div v-if="loading" class="loading-container">
      <div class="spinner"></div>
      <p>Loading, please wait...</p>
    </div>
    
    <div v-else>
      <div v-if="isLoggedIn" class="dashboard-section">
        <div class="user-tabs">
          <button class="tab-button" @click="currentTab = 'info'" :class="{ active: currentTab === 'info' }">Personal Info</button>
          <button class="tab-button" @click="currentTab = 'orders'" :class="{ active: currentTab === 'orders' }">Orders</button>
          <button class="tab-button" @click="currentTab = 'purchases'" :class="{ active: currentTab === 'purchases' }">Purchases</button>
        </div>
        
        <div v-if="currentTab === 'info'" class="tab-content">
          <personal-info v-if="userData && Object.keys(userData).length" :data="userData" />
        </div>
        <div v-if="currentTab === 'orders'" class="tab-content">
          <order-details v-if="orderData && orderData.length" :data="orderData" />
        </div>
        <div v-if="currentTab === 'purchases'" class="tab-content">
          <purchase-details v-if="purchaseData && purchaseData.length" :data="purchaseData" />
        </div>
      </div>
      
      <div v-else class="login-section">
        <h2>Welcome!</h2>
        <p>Please login to view your personal information and orders.</p>
        <router-link to="/login" class="login-button">Login Now</router-link>
      </div>
    </div>
  </div>

  <div v-if="favoriteData && favoriteData.length" class="favorite-products-section">
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

const currentTab = ref('info'); 

const logout = () => {
  userAuth.logout(); 
  router.push('/'); 
};

onMounted(() => {
  if (isLoggedIn.value) {
    userStore.fetchUsers();
  }
});
</script>

<style scoped>
.main-container {
  width: 100%;
  max-width: 1200px;
  margin: 20px auto;
  padding: 30px 0px;
  background-color: #f7f9fc;
  border-radius: 15px;
  position: relative; 
}

.logout-button {
  position: absolute; 
  top: 20px;
  right: 20px;
  padding: 8px 12px;
  background-color: #e74c3c; 
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.logout-button:hover {
  background-color: #c0392b;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 50px;
}

.dashboard-section {
  display: flex;
  flex-direction: column;
}

.user-tabs {
  display: flex;
  justify-content: center;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.tab-button {
  padding: 10px 20px;
  background: #ddd;
  border: none;
  cursor: pointer;
  transition: background 0.3s;
}

.tab-button.active, .tab-button:hover {
  background: #3498db;
  color: #fff;
}

.tab-content {
  margin: auto;
  padding: 12px;
  background: #fff;
  max-width: 800px;
  width: 100%;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.login-section {
  text-align: center;
}

.login-button {
  display: inline-block;
  padding: 10px 20px;
  margin-top: 15px;
  background-color: #3498db;
  color: white;
  border: none;
  border-radius: 5px;
  text-decoration: none; 
  transition: background-color 0.3s;
  font-size: 16px;
}

.login-button:hover {
  background-color: #2980b9;
}

.favorite-products-section {
  margin: 40px auto;
  text-align: center;
}

.favorite-title {
  font-size: 1.6em;
  color: #444;
  margin-bottom: 20px;
}

@media (max-width: 768px) {
  .logout-button {
    top: 8px;
    right: 10px;
    padding: 6px 10px; 
    font-size: 14px;
  }

  .user-tabs {
    justify-content: space-around; 
    margin-top: 7px;
  }

  .tab-button {
    flex: 1;
    margin: 5px;
  }
}
</style>
