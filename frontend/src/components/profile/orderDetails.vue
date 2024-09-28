<template>
  <!-- Current Orders -->
  <div class="current-orders">
    <h2 class="section-title">Current Orders</h2>
    <table v-if="isDesktop">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Total Amount</th>
          <th>Order Status</th>
          <th>Cost</th>
          <th>Address</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="data in orders" :key="data.id">
          <td>{{data.id}}</td>
          <td>{{data.total_amount}}</td>
          <td>{{data.order_status}}</td>
          <td>{{ data.shipping_cost }}</td>
          <td>{{data.shipping_address}}</td>
        </tr>
      </tbody>
    </table>

    <div v-else class="order-cards">
      <div v-for="data in orders" :key="data.id" class="order-card">
        <h3>Order ID: {{data.id}}</h3>
        <p>Total Amount: {{data.total_amount}}</p>
        <p>Order Status: {{data.order_status}}</p>
        <p>Cost: {{data.shipping_cost}}</p>
        <p>Address: {{data.shipping_address}}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, computed } from 'vue';

const props = defineProps({
  data: Object
});

const orders = computed(() => props.data);
const isDesktop = ref(window.innerWidth > 768);

window.addEventListener('resize', () => {
  isDesktop.value = window.innerWidth > 768;
});
</script>

<style scoped>
/* Styles for both table and cards */
.section-title {
  font-size: 1.8em;
  color: #444;
  margin-bottom: 20px;
  border-bottom: 2px solid #ddd;
  padding-bottom: 10px;
}

.current-orders {
  margin-bottom: 40px;
}

/* Desktop Table */
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
.order-cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.order-card {
  background-color: #f9f9f9;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  flex: 1 1 calc(100% - 20px);
  transition: background-color 0.3s;
}

.order-card:hover {
  background-color: #e3e3e3;
}
</style>
