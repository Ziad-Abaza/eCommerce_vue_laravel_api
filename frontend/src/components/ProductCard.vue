<template>
  <div class="product-card">
    <div v-if="discountValue > 0" class="discount-badge">
      {{ discountPercentage }}%
    </div>

    <div class="wishlist" @click.prevent="handleFavorite">
      <i :class="isFavorite ? 'fas fa-heart' : 'far fa-heart'"></i>
    </div>

    <img
      :src="productImage"
      :alt="product.product_name"
      class="product-image"
    />
    
    <h3>{{ product.product_name }}</h3>
    
    <div class="card-footer">
      <slot name="footer">
        <div class="price-container">
          <span class="card-price">${{ priceAfterDiscount }}</span>
          <span v-if="discountValue > 0" class="old-price">${{ productPrice }}</span>
        </div>
        <div class="btn-card">
          <button class="buy-btn" @click.prevent="handleAddCart">Buy</button>
          <router-link :to="{ name: 'details', params: { id: product.id } }">
            <button class="details-btn">View</button>
          </router-link>
        </div>
      </slot>
    </div>
  </div>
</template>


<script setup>
import { ref, computed, watch } from "vue";
import { useProductsStore } from "@/stores/productsStore.js";
import { useAuthStore } from "@/stores/authStore.js";

const productsStore = useProductsStore();
const userAuth = useAuthStore().isLoggedIn;
const props = defineProps({
  product: Object,
});

/*
|---------------------------------------------------
|> Track Favorite State for Each Product
|---------------------------------------------------
*/
let isFavorite = ref(
  userAuth
    ? props.product.favorites && props.product.favorites.length > 0
    : productsStore.localFavoriteItem.includes(props.product.id)
);


const favoriteId = computed(() => {
  return props.product.favorites && props.product.favorites.length > 0
    ? props.product.favorites[0].id
    : null;
});


/*
|---------------------------------------------------
|> Handle Adding/Removing Favorite
|---------------------------------------------------
*/
const handleFavorite = () => {
  try {
    isFavorite.value = !isFavorite.value;
    if (isFavorite.value == true) {
      productsStore.addToFavorite(props.product.id); 
      console.log("Adding to favorites");
    } else {
      productsStore.removeFavorite(favoriteId.value, props.product.id); 
      console.log("Removed from favorites");
    }
  } catch (error) {
    console.error(
      `Failed to update favorite status for Product ${props.product.id}:`,
      error.message
    );
  }
};

/*
|---------------------------------------------------
|> Compute Product Image URL
|---------------------------------------------------
*/
const productImage = computed(() =>
  props.product.details.length > 0
    ? props.product.details[0].image
    : "https://via.placeholder.com/640x480.png"
);

/*
|---------------------------------------------------
|> Compute Product Price
|---------------------------------------------------
*/
const productPrice = computed(() =>
  props.product.details.length > 0 ? props.product.details[0].price : "0.00"
);

/*
|---------------------------------------------------
|> Compute Product discount
|---------------------------------------------------
*/
const productDiscount = computed(() =>
  props.product.details.length > 0 ? props.product.details[0].discount : null
);

/*
|---------------------------------------------------
|> Compute Product Discount Details
|---------------------------------------------------
*/
const discountValue = computed(() => 
  productDiscount.value || 0
);

const priceAfterDiscount = computed(() => {
  return (productPrice.value - discountValue.value).toFixed(2);
});

const discountPercentage = computed(() => {
  return ((discountValue.value / productPrice.value) * 100).toFixed(1);
});



/*
|---------------------------------------------------
|> Handle Adding/Removing Cart
|---------------------------------------------------
*/
const handleAddCart = () => {
  try {
    productsStore.addToCart(props.product.id, 1, props.product.details[0].id);
  } catch (error) {
    console.error(
      `Failed to Add cart status for Product [${props.product.id}]:`,
      error.message
    );
  }
};
</script>

<style scoped>
.product-card {
  background-color: #f0f0f0;
  padding: 15px;
  border-radius: 15px;
  /* box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); */
  text-align: center;
  position: relative;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

.product-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 15px;
}

.product-card h3 {
  font-size: 1.1em;
  margin: 10px 0;
}

.product-card .price {
  font-size: 1.2em;
  color: #333;
  margin-bottom: 15px;
}

.btn-card {
  display: flex;
  gap: 10px;
}

.details-btn {
  background-color: #f8b704;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 20px;
  cursor: pointer;
  display: inline-block;
  transition: background-color 0.3s ease;
}

.details-btn:hover {
  background-color: #f8b704cc;
}

.buy-btn {
  background-color: #333;
  color: white;
  border: none;
  padding: 10px 15px;
  border-radius: 20px;
  cursor: pointer;
  display: inline-block;
  transition: background-color 0.3s ease;
}

.buy-btn:hover {
  background-color: #555;
}

.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 16px;
  padding-top: 8px;
  border-top: 1px solid #e0e0e0;
}

.price-container{
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column-reverse;
}

.product-card .card-price {
  font-size: 1.25em;
  font-weight: bold;
  color: #e67e22;
  margin-right: 10px;
}

.product-card .old-price {
  font-size: 0.9em;
  color: #888;
  text-decoration: line-through;
  margin-left: 5px;
  position: absolute;
  top: -15px;
  left: -10px;
}


.wishlist {
  position: absolute;
  top: 10px;
  right: 10px;
  color: red;
  cursor: pointer;
}
.discount-badge {
  position: absolute;
  top: 0;
  left: 0;
  background-color: red;
  color: white;
  padding: 5px 10px;
  font-size: 0.9em;
  font-weight: bold;
  border-top-left-radius: 10px;
  border-bottom-right-radius: 10px;
}

</style>
