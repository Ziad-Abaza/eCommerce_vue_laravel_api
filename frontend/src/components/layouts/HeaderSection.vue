<template>
  <header class="header">
    <div class="profile">
      <img src="@/assets/logo.png" alt="User Profile" class="profile-img" />
    </div>
    <div class="search-bar">
      <input
        type="text"
        placeholder="Search"
        v-model="searchQuery"
        @input="handleSearch"
      />
      <p v-if="productsStore.noSearchResults" class="no-results">No results found</p>
    </div>
    <div class="cart">
      <router-link to="/cart">
        <i class="fas fa-shopping-cart cart-icon"></i>
        <span class="cart-count" v-if="ProductInCart > 0">{{ ProductInCart }}</span>
      </router-link>
    </div>
  </header>
</template>

<script setup>
import { ref, computed } from "vue";
import { useProductsStore } from "@/stores/productsStore.js";

const productsStore = useProductsStore();
const searchQuery = ref('');

const handleSearch = () => {
  productsStore.searchProduct(searchQuery.value);
};

const ProductInCart = computed(() => {
  return productsStore.getCountCart;
});
</script>

<style scoped>
.header {
  display: flex;
  align-items: center;
  justify-content: start;
  padding: 10px 0;
  background-color: #ffffff;
}

.profile-img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}

.search-bar {
  display: flex;
  align-items: start;
  margin-left: 10px;
  margin-right: 10px;
  background-color: #f0f0f0;
  border-radius: 10px;
  padding: 5px 10px;
  position: relative; 
}

.search-bar input {
  border: none;
  background-color: transparent;
  outline: none;
  flex: 1;
  max-width: 230px;
}

.no-results {
  position: absolute;
  top: 35px;
  left: 15px;
  color: #f05c5c;
  font-weight: 700;
  font-family: system-ui;
  letter-spacing: 0.5px;
  transition: 0.5s;
  padding: 7px 4px;
  border: 2px solid #f0f0f0;
  border-radius: 11px;
}

.cart{
  position: relative;
}

.cart-icon {
  font-size: 24px;
  color: #333;
}

.cart-icon:hover {
  color: #555;
}

.cart-count {
  position: absolute;
  top: -8px;
  right: -7px;
  background-color: red;
  color: white;
  border-radius: 50%;
  padding: 1px 5px;
  font-size: 12px;
  font-weight: bold;
}
</style>
