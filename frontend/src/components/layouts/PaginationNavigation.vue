<template>
  <div class="pagination">
    <button
      class="pagination-button"
      :disabled="currentPage === 1"
      @click="prevPage"
    >
      &laquo;
    </button>

    <div class="pagination-number-container">
      <span v-for="page in totalPages" :key="page" class="pagination-number">
        <button
          :class="{ active: currentPage === page }"
          @click="goToPage(page)"
        >
          {{ page }}
        </button>
      </span>
    </div>

    <button
      class="pagination-button"
      :disabled="currentPage === totalPages"
      @click="nextPage"
    >
      &raquo;
    </button>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useProductsStore } from "@/stores/productsStore"; 

const productsStore = useProductsStore();
const currentPage = computed(() => productsStore.currentPage);
const totalPages = computed(() => productsStore.totalPages);

// Method to navigate to the previous page
const prevPage = () => {
  if (currentPage.value > 1) {
    productsStore.fetchProducts(currentPage.value - 1);
  }
};

// Method to navigate to the next page
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    productsStore.fetchProducts(currentPage.value + 1);
  }
};

// Method to navigate to a specific page
const goToPage = (page) => {
  productsStore.fetchProducts(page);
};
</script>

<style>
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
}

.pagination-button {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 8px 16px;
  margin: 0 4px;
  cursor: pointer;
  font-size: 16px;
}

.pagination-button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.pagination-number-container {
  display: flex;
  flex-wrap: nowrap;
}

.pagination-number {
  margin: 0 4px;
}

.pagination-number button {
  background-color: #fff;
  border: 1px solid #ccc;
  padding: 8px 16px;
  cursor: pointer;
  font-size: 16px;
}

.pagination-number button.active {
  background-color: #007bff;
  color: #fff;
}

.pagination-number button:hover {
  background-color: #e2e2e2;
}
</style>
