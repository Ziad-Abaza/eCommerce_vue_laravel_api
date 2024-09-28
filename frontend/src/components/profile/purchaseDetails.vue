<template>
  <!-- Previous Orders -->
  <div class="previous-orders">
    <h2 class="section-title">Previous Orders</h2>
    <table v-if="isDesktop">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Date</th>
          <th>Quantity</th>
          <th>Total Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="data in purchases" :key="data.id">
          <td>{{ data.id }}</td>
          <td>{{ data.purchase_date }}</td>
          <td>{{ data.quantity }}</td>
          <td>{{ data.total_price }}</td>
        </tr>
      </tbody>
    </table>

    <div v-else class="purchase-cards">
      <div v-for="data in purchases" :key="data.id" class="purchase-card">
        <h3>Order ID: {{ data.id }}</h3>
        <p><strong>Date:</strong> {{ data.purchase_date }}</p>
        <p><strong>Quantity:</strong> {{ data.quantity }}</p>
        <p><strong>Total Amount:</strong> {{ data.total_price }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, computed, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  data: Object
});

const purchases = computed(() => props.data);
const isDesktop = ref(window.innerWidth > 768);

const updateIsDesktop = () => {
  isDesktop.value = window.innerWidth > 768;
};

onMounted(() => {
  window.addEventListener('resize', updateIsDesktop);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', updateIsDesktop);
});
</script>

<style scoped>
.section-title {
  font-size: 1.8em;
  color: #444;
  margin-bottom: 20px;
  border-bottom: 2px solid #ddd;
  padding-bottom: 10px;
}

.previous-orders {
  margin-bottom: 40px;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
}

table th,
table td {
  padding: 12px;
  text-align: left;
  border: 1px solid #ddd;
}

table th {
  background-color: #f7f7f7;
  font-weight: bold;
  color: #555;
}

table tbody tr:nth-child(even) {
  background-color: #f9f9f9;
}

table tbody tr:hover {
  background-color: #f1f1f1;
}

/* Card view for mobile */
.purchase-cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.purchase-card {
  background-color: #f9f9f9;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  flex: 1 1 calc(100% - 20px); 
  transition: background-color 0.3s;
}

.purchase-card:hover {
  background-color: #e3e3e3;
}

@media (max-width: 600px) {
  .purchase-card {
    flex: 1 1 calc(100% - 10px); 
  }
}
</style>
