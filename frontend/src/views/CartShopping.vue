<template>
  <div class="cart-container">
    <h1 class="cart-title">Shopping Cart</h1>

    <div v-if="cart.length === 0" class="empty-cart">
      <p>Your cart is empty</p>
    </div>

    <div v-else>
      <div class="cart-items">
        <div class="cart-item" v-for="(item, index) in cart" :key="index">
          <img
            :src="item.product.details.image"
            alt="Product Image"
            class="cart-item-img"
          />
          <div class="cart-item-details">
            <h3>{{ item.product.product_name }}</h3>
            <p class="item-description">{{ item.product.description }}</p>
            <p class="item-price">Price: ${{ item.product.details.price }}</p>
            <div class="quantity-controls">
              <button
                @click="updateQuantity(index, item.quantity - 1)"
                class="quantity-btn"
              >
                -
              </button>
              <span class="quantity">{{ item.quantity }}</span>
              <button
                @click="updateQuantity(index, item.quantity + 1)"
                class="quantity-btn"
              >
                +
              </button>
            </div>
            <button @click="removeFromCart(index)" class="remove-btn">
              Remove
            </button>
          </div>
        </div>
      </div>

      <div class="cart-summary">
        <h2>Cart Summary</h2>
        <p class="total-price">Total: ${{ totalPrice.toFixed(2) }}</p>
        <button class="checkout-btn">Checkout</button>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from "vue";
import { useProductsStore } from "@/stores/productsStore.js";
import { useAuthStore } from "@/stores/authStore";
import axios from "axios";


const productsStore = useProductsStore();
const authStore = useAuthStore();
const cart = ref([]);

onMounted(() => {
  productsStore.fetchCartData();
  cart.value = productsStore.getCartItems;
});

/*
|----------------------------------------------------
|> Calculate total price of items in the cart
|----------------------------------------------------
*/
const totalPrice = computed(() => {
  return cart.value.reduce(
    (acc, item) => acc + item.product.details.price * item.quantity,
    0
  );
});

/*
|----------------------------------------------------
|> Update the quantity of a specific item in the cart
|----------------------------------------------------
*/
function updateQuantity(index, quantity) {
  if (quantity < 1) return; 

  const item = cart.value[index];

  if (item && quantity > item.product.details.stock) return; 

  if (item) {
    item.quantity = quantity; 
    productsStore.updateProductQuantity(
      item.id,
      item.product.product_id,
      quantity,
      item.product.details.id 
    );
  }
}

/*
|----------------------------------------------------
|> Remove item from cart based on index
|----------------------------------------------------
*/
function removeFromCart(index) {
  const item = cart.value[index];
  if (item) {
    cart.value.splice(index, 1); 
    productsStore.removeFromCart(item.id);
  }
}
</script>


<style scoped>
.cart-container {
  padding: 20px;
  max-width: 900px;
  margin: 0 auto;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.cart-title {
  text-align: center;
  margin-bottom: 25px;
  font-size: 28px;
  color: #333;
  font-weight: bold;
}

.empty-cart {
  text-align: center;
  font-size: 20px;
  color: #888;
}

.cart-items {
  margin-bottom: 25px;
}

.cart-item {
  display: flex;
  align-items: center;
  margin-bottom: 25px;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 10px;
  background-color: #f9f9f9;
}

.cart-item-img {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 8px;
  margin-right: 20px;
}

.cart-item-details {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.item-description {
  font-size: 14px;
  color: #666;
  margin: 10px 0;
}

.item-price {
  font-size: 16px;
  color: #333;
  margin-bottom: 10px;
}

.quantity-controls {
  display: flex;
  align-items: center;
  margin-top: 10px;
}

.quantity-btn {
  background-color: #e0e0e0;
  border: none;
  padding: 6px 12px;
  cursor: pointer;
  font-size: 18px;
  border-radius: 4px;
  transition: background-color 0.3s;
}

.quantity-btn:hover {
  background-color: #ccc;
}

.quantity {
  margin: 0 10px;
  font-size: 16px;
}

.remove-btn {
  background-color: #e74c3c;
  color: white;
  border: none;
  padding: 12px 18px;
  border-radius: 25px;
  cursor: pointer;
  margin-top: 15px;
  transition: background-color 0.3s;
}

.remove-btn:hover {
  background-color: #c0392b;
}

.cart-summary {
  text-align: center;
  padding-top: 20px;
  border-top: 1px solid #eee;
  margin-top: 20px;
}

.total-price {
  font-size: 20px;
  color: #333;
  margin-bottom: 20px;
}

.checkout-btn {
  background-color: #2ecc71;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 25px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.checkout-btn:hover {
  background-color: #27ae60;
}

@media (max-width: 768px) {
  .cart-item {
    flex-direction: column;
    align-items: flex-start;
  }

  .cart-item-img {
    width: 100%;
    height: auto;
    margin-bottom: 10px;
  }
}
</style>
