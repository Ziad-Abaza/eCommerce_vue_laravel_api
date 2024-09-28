<template>
  <div class="product-page" v-if="productData && !productsStore.loading">
    <!-- Product Details Section -->
    <div
      class="product-details"
      v-if="productData.details && productData.details.length > 0"
    >
      <div class="product-image">
        <img :src="selectedDetail.image" :alt="productData.product_name" />
        <div class="thumbnails">
          <img
            v-for="(detail, index) in productData.details"
            :key="index"
            :src="detail.image"
            :alt="productData.product_name"
            @click="selectImage(detail)"
          />
        </div>
      </div>
      <div class="product-info">
        <h1>{{ productData.product_name }}</h1>
        <p class="price">${{ priceAfterDiscount }}</p>
        <p class="description">{{ productData.description }}</p>

        <div class="color-options">
          <p>Color</p>
          <div
            v-for="detail in productData.details"
            :key="detail.id"
            class="color-box"
            :style="{ backgroundColor: detail.color }"
            @click="selectColor(detail)"
          ></div>
        </div>

        <div class="size-options">
          <p>Size</p>
          <button
            v-for="detail in productData.details"
            :key="detail.id"
            class="size-button"
            @click="selectSize(detail)"
          >
            {{ detail.size }}
          </button>
        </div>

         <div class="btn-action-group">
           <div class="count-product">
            <button class="minus" @click="decreaseCount">-</button>
            <span>{{ countProductInCart }}</span>
            <button class="plus" @click="increaseCount">+</button>
           </div>
        <div class="actions">
          <div class="order-action">
            <button class="order-button" @click.prevent="handleAddCart">
              Order Now
            </button>
          </div>
          <button class="favorite-button" @click.prevent="handleFavorite">
            Add to Favorites
          </button>
        </div>
         </div>

        <!-- Vendor Information Section -->
        <div class="vendor-info" v-if="productData.vendor">
          <h2>Seller By:</h2>
          <div class="vendor-details">
            <img
              :src="productData.vendor.image"
              :alt="productData.vendor.name"
              class="vendor-image"
            />
            <div class="vendor-text">
              <p class="vendor-name">{{ productData.vendor.name }}</p>
              <p class="vendor-email">{{ productData.vendor.email }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Comments Section -->
    <div
      class="comments-section"
      v-if="productData.comments && productData.comments.length > 0"
    >
      <h2>Customer Reviews</h2>
      <swiper
        class="comments-container"
        :slides-per-view="1"
        space-between="10"
        :breakpoints="{
          640: { slidesPerView: 1 },
          768: { slidesPerView: 2 },
          1024: { slidesPerView: 3 },
        }"
      >
        <swiper-slide
          class="comment-card"
          v-for="comment in productData.comments"
          :key="comment.id"
        >
          <div class="card-container">
            <p class="comment-text">{{ comment.comment }}</p>
            <div class="rating">
              <span v-for="n in comment.rating" :key="n" class="star">★</span>
              <span
                v-for="n in 5 - comment.rating"
                :key="'empty-' + n"
                class="star-empty"
                >☆</span
              >
            </div>
            <p class="comment-date">{{ formatDate(comment.comment_date) }}</p>
          </div>
        </swiper-slide>
      </swiper>
    </div>

  </div>
    <!-- Related Products Section -->
    <div class="related-products">
      <h2>More Products</h2>
      <swiper :slides-per-view="4" space-between="10" loop>
        <swiper-slide
          v-for="product in relatedProducts"
          :key="product.id"
          class="related-products-container"
        >
          <ProductCard :product="product" />
        </swiper-slide>
      </swiper>
    </div>
        <FooterNavigation />
</template>

<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useProductsStore } from "@/stores/productsStore.js";
import { useNotificationStore } from "@/stores/notificationStore";
import FooterNavigation from '@/components/layouts/FooterNavigation.vue';
import { useRoute } from "vue-router";
import ProductCard from "@/components/ProductCard.vue";

import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";

const route = useRoute();
const productsStore = useProductsStore();
const notificationStore = useNotificationStore()
const productId = ref(parseInt(route.params.id));
const productData = ref({});
let brand = "";
const relatedProducts = ref([]);
const selectedDetail = ref({});
const countProductInCart = ref(1);
const isFavorite = ref(false);
const fetchProductData = async () => {
  await productsStore.getProductById(productId.value);
  productData.value = productsStore.productDetails;

  brand = productsStore.productDetails.brand;

  relatedProducts.value = productsStore.originalProducts.filter((product) => {
    return product.brand === brand;
  });

  if (productData.value.details && productData.value.details.length > 0) {
    selectedDetail.value = productData.value.details[0];
  }
};

onMounted(fetchProductData);

watch(
  () => route.params.id,
  (newId) => {
    productId.value = parseInt(newId);
    fetchProductData();
  }
);

const priceAfterDiscount = computed(() => {
  return (selectedDetail.value.price - selectedDetail.value.discount).toFixed(2);
});

function formatDate(dateString) {
  const options = { year: "numeric", month: "long", day: "numeric" };
  return new Date(dateString).toLocaleDateString(undefined, options);
}

function selectColor(detail) {
  selectedDetail.value = detail;
}

function selectSize(detail) {
  selectedDetail.value = detail;
}

function selectImage(detail) {
  selectedDetail.value = detail;
}

function increaseCount() {
  if (countProductInCart.value < selectedDetail.value.stock) {
    countProductInCart.value++;
  }
}

function decreaseCount() {
  if (countProductInCart.value > 1) {
    countProductInCart.value--;
  }
}



const handleAddCart = async () => {
  try {
    await productsStore.addToCart(
      productId.value,
      countProductInCart.value,
      selectedDetail.value.id
    );
    console.log(`Product ${productId.value} added to Cart.`);
  } catch (error) {
    console.error(
      `Failed to add product (${productId.value}) to Cart because:`,
      error.message
    );
  }
};

const handleFavorite = async () => {
  try {
    if (isFavorite.value == false) {
      productsStore.addToFavorite(productId.value); 
      console.log("Adding to favorites");
      notificationStore.setNotifications('product adding successfully');
      isFavorite.value = true;
    }
  } catch (error) {
    console.error(
      `Failed to update favorite status for Product ${productId.value}:`,
      error.message
    );
  }
};

</script>

<style scoped>
.product-page {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  padding: 20px;
}

.product-details {
  display: flex;
  gap: 2rem;
}

.product-image {
  flex: 1;
}

.product-image img {
  width: 100%;
  border-radius: 5px;
}

.thumbnails {
  display: flex;
  gap: 0.5rem;
}

.thumbnails img {
  width: 50px;
  height: 50px;
  border-radius: 5px;
}

.product-info {
  flex: 1;
}

.price {
  font-size: 1.5rem;
  color: #6200ee;
}

.description {
  margin-top: 1rem;
  color: #757575;
}

.color-options,
.size-options {
  margin-top: 1rem;
}

.color-box {
  display: inline-block;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  margin-right: 5px;
  border: 2px solid #6200ee;
}

.size-button {
  margin-right: 5px;
  padding: 5px 10px;
  border: 1px solid #6200ee;
  background-color: transparent;
  border-radius: 5px;
  cursor: pointer;
}

.btn-action-group{
  display: flex;
  align-items: center;
  justify-content: start;
  gap: 20px;
  margin-top: 1rem;
}

.count-product{
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: row;
  width: fit-content;
}

.count-product .plus,  
.count-product .minus{
  width: 25px;
  height: 25px;
}

.count-product span{
  width: 25px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.actions {
  display: flex;
  gap: 1rem;
}

.order-action {
  display: flex;
  /* align-items: center; */
  justify-content: center;
  gap: 3px;
}

.order-action input {
  width: 50px;
  border: 1px solid #6200ee;
  border-radius: 5px;
  text-align: center;
  font-size: 18px;
}

.order-action input:hover,
.order-action input:focus-visible,
.order-action input:focus {
  border: 1px solid #6200ee;
  outline: none;
}

.order-button,
.favorite-button {
  padding: 10px 20px;
  border: none;
  background-color: #6200ee;
  color: white;
  border-radius: 5px;
  cursor: pointer;
}

.vendor-info {
  margin-top: 1rem;
}

.vendor-info h2 {
  font-size: 1.5rem;
}

.vendor-details {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.vendor-image {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
}

.vendor-text {
  display: flex;
  flex-direction: column;
}

.vendor-name {
  font-size: 0.8rem;
  font-weight: bold;
}

.vendor-email {
  color: #757575;
}

.related-products {
  margin: 2rem 0 4rem 20px;
}

.related-products-container {
  padding: 20px 0;
}

.related-items {
  display: flex;
  gap: 2rem;
  max-width: 100%;
  padding: 10px 0px;
  overflow: auto;
  margin: auto;
}

.swiper {
  width: 100%;
  height: 100%;
  margin: 25px 0;
}

.swiper-slide {
  min-width: 280px;
  text-align: center;
  font-size: 18px;
  background: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
}
.comments-section {
  margin: 2rem 0;
}

.comments-container {
  padding: 20px 5px;
}

.comment-card {
  background-color: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 1rem;
  min-height: 220px;
}

.card-container {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.comment-text {
  font-size: 1rem;
  color: #333;
}

.rating {
  color: #ffc107;
  font-size: 1.2rem;
}

.star-empty {
  color: #ccc;
}

.comment-date {
  font-size: 0.85rem;
  color: #757575;
}
@media (max-width: 768px) {
  .product-details {
    flex-direction: column;
  }
}
</style>
