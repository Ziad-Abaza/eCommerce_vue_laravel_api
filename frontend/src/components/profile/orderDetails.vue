<template>
  <div class="current-orders">
    <h2 class="section-title">Current Orders</h2>

    <div class="order-cards">
      <div v-for="data in orders" :key="data.id" class="order-card">
        <div class="card-header">
          <h3>Order ID: <span>{{ data.id }}</span></h3>
          <span class="status">{{ data.order_status }}</span>
        </div>
        <div class="order-details">
          <p><strong>Total Amount:</strong> <span class="amount">{{ data.total_amount }}</span></p>
          <p><strong>Shipping Cost:</strong> <span class="cost">{{ data.shipping_cost }}</span></p>
          <p><strong>Shipping Address:</strong> <span class="address">{{ data.shipping_address }}</span></p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
  data: Object
});

const orders = computed(() => props.data);
</script>

<style scoped>
.section-title {
  font-size: 2em;
  color: #333;
  margin-bottom: 20px;
  text-align: center;
  border-bottom: 3px solid #007bff;
  padding-bottom: 10px;
}

.current-orders {
  margin-bottom: 40px;
  padding: 20px;
}

.order-cards {
  display: flex;
  flex-direction: column;
  gap: 20px;
  max-height: 415px;
  overflow-y: auto;
}

/* تحسين شكل شريط التمرير */
.order-cards::-webkit-scrollbar {
  width: 12px; /* عرض الشريط */
}

.order-cards::-webkit-scrollbar-track {
  background: #f1f1f1; /* لون الخلفية للشريط */
  border-radius: 10px; /* زوايا دائرية للخلفية */
}

.order-cards::-webkit-scrollbar-thumb {
  background: #007bff; /* لون الشريط نفسه */
  border-radius: 10px; /* زوايا دائرية للشريط */
}

.order-cards::-webkit-scrollbar-thumb:hover {
  background: #0056b3; /* لون الشريط عند التمرير */
}

.order-card {
  background-color: #ffffff;
  padding: 20px;
  margin-right: 17px;
  border-radius: 15px;
  transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  position: relative;
  border-left: 5px solid #007bff; /* Border to highlight the card */
}

.order-card:hover {
  background-color: #f1f1f1;
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.card-header h3 {
  font-size: 1.5em;
  margin: 0;
  color: #333;
}

.status {
  font-size: 1.2em;
  font-weight: bold;
  color: #28a745; /* Green for completed orders */
}

.order-details {
  border-top: 2px solid #007bff;
  padding-top: 10px;
}

.order-card p {
  margin: 8px 0;
  font-size: 1.1em;
}

.amount, .cost, .address {
  color: #007bff;
  font-weight: bold;
}
</style>
