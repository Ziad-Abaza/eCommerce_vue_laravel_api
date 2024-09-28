<template>
  <!-- Previous Orders -->
  <div class="previous-orders">
    <h2 class="section-title">Previous Orders</h2>

    <div class="purchase-cards">
      <div v-for="data in purchases" :key="data.id" class="purchase-card">
        <div class="card-header">
          <h3>Order ID: <span>{{ data.id }}</span></h3>
        </div>
        <div class="purchase-details">
          <p><strong>Quantity:</strong> <span class="quantity">{{ data.quantity }}</span></p>
          <p><strong>Total Amount:</strong> <span class="total">{{ data.total_price }}</span></p>
        </div>
        <div class="card-footer">
          <span class="date">{{ data.purchase_date }}</span>
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

const purchases = computed(() => props.data);
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

.previous-orders {
  margin-bottom: 40px;
  padding: 20px;
}

.purchase-cards {
  display: flex;
  flex-direction: column;
  gap: 20px;
  max-height: 415px;
  overflow-y: auto;
}

/* تحسين شكل شريط التمرير */
.purchase-cards::-webkit-scrollbar {
  width: 12px; /* عرض الشريط */
}

.purchase-cards::-webkit-scrollbar-track {
  background: #f1f1f1; /* لون الخلفية للشريط */
  border-radius: 10px; /* زوايا دائرية للخلفية */
}

.purchase-cards::-webkit-scrollbar-thumb {
  background: #007bff; /* لون الشريط نفسه */
  border-radius: 10px; /* زوايا دائرية للشريط */
}

.purchase-cards::-webkit-scrollbar-thumb:hover {
  background: #0056b3; /* لون الشريط عند التمرير */
}

.purchase-card {
  background-color: #ffffff;
  padding: 20px;
  margin-right: 17px;
  border-radius: 15px;
  transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  position: relative;
  border-left: 5px solid #007bff; /* Border to highlight the card */
}

.purchase-card:hover {
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

.purchase-details {
  border-top: 2px solid #007bff;
  padding-top: 10px;
}

.purchase-card p {
  margin: 8px 0;
  font-size: 1.1em;
}

.quantity, .total {
  color: #007bff;
  font-weight: bold;
}

.card-footer {
  margin-top: 15px; 
  text-align: left; 
}

.date {
  font-size: 1.1em;
  color: #6c757d; 
}
</style>
