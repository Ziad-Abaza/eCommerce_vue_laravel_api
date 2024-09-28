<template>
  <div class="home">
    <HeaderSection />
    <FilterSection />
    <div class="products-grid">
      <ProductCard v-for="product in products" :key="product.id" :product="product" />
    </div>
    <FooterNavigation />
    <PaginationNavigation v-if="products.length > 0" /> 
  </div>
    <FooterSection />
</template>

<script setup>
import HeaderSection from '@/components/layouts/HeaderSection.vue';
import FilterSection from '@/components/layouts/FilterSection.vue';
import ProductCard from '@/components/ProductCard.vue';
import FooterNavigation from '@/components/layouts/FooterNavigation.vue';
import PaginationNavigation from '@/components/layouts/PaginationNavigation.vue';
import FooterSection from '@/components/layouts/FooterSection.vue';
import { useProductsStore } from '@/stores/productsStore';
import { useNotificationStore } from '@/stores/notificationStore';
import { onMounted, computed } from 'vue';

const productsStore = useProductsStore();
const notificationStore = useNotificationStore();

onMounted(() => {
  productsStore.fetchProducts();
  productsStore.fetchCartData();
  productsStore.findBrands();
  productsStore.getBrands;
  notificationStore.fetchNotifications();
});

const products = computed(() => productsStore.getProducts);
</script>

<style scoped>
.home {
  padding: 16px;
  background-color: #ffffff;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: 16px;
  margin: 16px 0;
}
</style>
