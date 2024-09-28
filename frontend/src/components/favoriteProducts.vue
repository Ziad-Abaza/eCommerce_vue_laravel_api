<template>
  <div class="favorite-products">
    <h2>Your Favorite Products</h2>
    <div v-if="favorites.length === 0">
      <p>You have no favorite products yet.</p>
    </div>
    <swiper :slides-per-view="slidesPerView" space-between="10" loop>
      <swiper-slide v-for="data in favorites" :key="data.id" class="related-products-container">
        <div class="favorite-info">
          <h3>{{ data.product_name }}</h3>
          <p>{{ data.description }}</p>
          <div class="footer-card">
            <p><strong>Brand:</strong> {{ data.brand }}</p>
            <router-link :to="{ name: 'details', params: { id: data.id } }" class="view-btn">View</router-link>
          </div>
        </div>
      </swiper-slide>
    </swiper>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
import { useUserStore } from '@/stores/userStore';
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/swiper-bundle.css";

const userStore = useUserStore();

const props = defineProps({
  data: Object
});

const favorites = computed(() => props.data || []);

const slidesPerView = ref(3);

const updateSlidesPerView = () => {
  if (window.innerWidth < 768) {
    slidesPerView.value = 1; 
  } else if (window.innerWidth < 1024) {
    slidesPerView.value = 2; 
  } else {
    slidesPerView.value = 3; 
  }
};

onMounted(() => {
  updateSlidesPerView();
  window.addEventListener('resize', updateSlidesPerView);
});

onBeforeUnmount(() => {
  window.removeEventListener('resize', updateSlidesPerView);
});
</script>


<style scoped>
.related-products-container {
  background-color: white;
  height: 240px;
  /* border-radius: 12px; */
  position: relative;
  margin-bottom: 25px;
}
.favorite-products {
  margin-top: 20px;
  padding: 10px;
  background-color: #f4f4f4;
  border-radius: 12px;
}

.favorite-products h2 {
  font-size: 1.8em;
  margin-bottom: 15px;
  text-align: center;
  color: #333;
}

.favorites-slider {
  width: 100%;
  padding: 10px 0;
}

.favorite-card {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s, box-shadow 0.3s;
}

.favorite-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

.favorite-info {
  padding: 15px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.favorite-info h3 {
  font-size: 1.4em;
  margin-bottom: 10px;
  color: #555;
}

.favorite-info p {
  font-size: 0.9em;
  color: #666;
}

.footer-card {
  position: absolute;
  bottom: 7px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}
.view-btn {
  margin-top: 10px;
  padding: 8px 12px;
  font-size: 0.9em;
  color: #fff;
  background-color: #f8b704;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  text-decoration-line: none;
}

.view-btn:hover {
  background-color: #f8a304;
}

@media (max-width: 768px) {
  .favorite-products h2 {
    font-size: 1.5em;
  }

  .favorites-slider {
    padding: 5px 0;
  }

  .favorite-card {
    padding: 10px;
  }
}
</style>
