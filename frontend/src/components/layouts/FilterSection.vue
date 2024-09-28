<template>
  <section class="filter-section">
    <h2>Popular</h2>
    <div class="filter-buttons">
      <button 
        @click="filterByBrand('All')" 
        :class="['filter-btn', { active: selectedBrand === 'All' }]">
        All
      </button>
      <button
        v-for="brand in brands"
        @click="filterByBrand(brand)"
        :key="brand"
        :class="['filter-btn', { active: selectedBrand === brand }]"
      >
        {{ brand }}
      </button>
    </div>
  </section>
</template>

<script setup>
import { useProductsStore } from "@/stores/productsStore.js";
import { ref, onMounted, computed } from "vue";

const productsStore = useProductsStore();

const selectedBrand = ref('All');

onMounted(() => {
  productsStore.findBrands();
});

const brands = computed(() => productsStore.getBrands);

const filterByBrand = (brand) => {
  selectedBrand.value = brand; 
  productsStore.filterByBrand(brand); 
};
</script>

<style scoped>
.filter-buttons {
  display: flex;
  align-items: center;
  justify-content: space-evenly;
  gap: 10px;
  flex-wrap: wrap;
  flex-grow: 1;
}

.filter-section {
  padding: 20px 0;
  background-color: #ffffff;
}

.filter-section h2 {
  margin: 0;
  margin-bottom: 15px;
  font-size: 1.5em;
}

.filter-btn {
  flex: 1;
  padding: 8px 12px;
  background-color: #f0f0f0;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  font-size: 14px;
  max-width: 170px;
  min-width: fit-content;
  transition: background-color 0.3s; 
}

.filter-btn.active {
  background-color: #333; 
  color: #fff; 
}

@media (max-width: 768px) {
  .filter-buttons {
    flex-wrap: wrap;
  }
}

@media (max-width: 480px) {
  .filter-buttons {
    flex-wrap: wrap;
    gap: 5px;
  }
}
</style>
