import { defineStore } from "pinia";
import axios from "axios";
import { useAuthStore } from "@/stores/authStore";
import { useNotificationStore } from "@/stores/notificationStore";
import { debounce } from "lodash";

export const useProductsStore = defineStore("products", {
  state: () => ({
    originalProducts: [],
    products: [],
    cartItems: [],
    brands: [],
    productDetails: [],
    localCardItem: [],
    localFavoriteItem: [],
    currentPage: 1,
    totalPages: null,
    loading: false,
    error: null,
    noSearchResults: false,
    itemsPerPage: 30,
  }),
  getters: {
    getProducts(state) {
      return state.products;
    },
    getCartItems(state) {
      return state.cartItems;
    },
    getCountCart(state) {
      return state.cartItems.length;
    },
    getBrands(state) {
      return state.brands;
    },
    getLocalCardItem(state) {
      return state.localCardItem;
    },
    getLocalFavoriteItem(state) {
      return state.localFavoriteItem;
    },
  },
  actions: {
    /*
    |==========================================================
    |===========>        Fetch and Get Data        <=========== 
    |==========================================================
    */
    async fetchProducts(page = 1) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get(`/api/products/limited?page=${page}`);
        this.originalProducts = response.data.data;
        this.products = response.data.data;
        this.currentPage = response.data.meta.current_page;
        this.totalPages = response.data.meta.last_page;
      } catch (err) {
        this.error = "Failed to fetch products";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },

    async getProductById(id) {
      this.loading = true;
      try {
        const response = await axios.get(`/api/products/${id}/`);
        this.productDetails = response.data.data;
      } catch (error) {
        this.error = error.message;
      } finally {
        this.loading = false;
      }
    },

    findBrands() {
      this.loading = true;
      this.error = null;
      try {
        const brandSet = new Set();
        this.originalProducts.forEach((product) => {
          if (product.brand) {
            brandSet.add(product.brand);
          }
        });
        this.brands = Array.from(brandSet);
      } catch (err) {
        this.error = "Failed to fetch brands";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },

    /*
    |==========================================================
    |===========>          Cart Functions          <=========== 
    |==========================================================
    */
    async fetchCartData() {
      this.loading = true;
      this.error = null;
      const userAuth = useAuthStore().isLoggedIn;
      try {
        if (userAuth) {
          const response = await axios.get("/api/cart/");
          this.cartItems = response.data.data;
          this.findBrands();
        }
      } catch (err) {
        this.error = "Failed to fetch cart data";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },

    async addToCart(productId, quantity, product_detail_id) {
      this.loading = true;
      this.error = null;
      const authStore = useAuthStore();
      const notificationStore = useNotificationStore();

      try {
        if (authStore.isLoggedIn) {
          const product = this.originalProducts.find(
            (product) => product.id === productId
          );
          if (!product) {
            throw new Error("Product not found");
          }
          const response = await axios.post("/api/cart/", {
            product_id: productId,
            quantity: quantity,
            product_detail_id: product_detail_id,
          });
        } else {
          this.localCardItem.push({
            product_id: productId,
            quantity: quantity,
            product_detail_id: product_detail_id,
          });
        }
        this.fetchCartData();
        notificationStore.setNotifications("Product added to Cart");
      } catch (err) {
        this.error = "Failed to fetch cart data";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },

    async removeFromCart(productId) {
      this.loading = true;
      this.error = null;
      const authStore = useAuthStore();
      try {
        if (authStore.isLoggedIn) {
          const product = this.originalProducts.find(
            (product) => product.id === productId
          );
          if (!product) {
            throw new Error("Product not found");
          }
          const response = await axios.delete(`/api/cart/${productId}`);
        } else {
          const index = this.localCardItem.findIndex(
            (item) => item.product_id === productId
          );
          if (index !== -1) {
            this.localCardItem.splice(index, 1);
          }
        }
        this.fetchCartData();
      } catch (err) {
        this.error = "Failed to delete item from cart data";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },

    async updateProductQuantity(cartId, productId, quantity, details_id) {
      if (this.loading) return;
      this.loading = true;
      this.error = null;

      try {
        const authStore = useAuthStore();
        const userAuth = authStore.isLoggedIn;

        if (userAuth) {
          await axios.post(`/api/cart/${cartId}`, {
            product_id: productId,
            quantity: quantity,
            product_detail_id: details_id,
          });
        } else {
          const localItem = this.localCardItem.find(
            (item) =>
              item.product_id === productId &&
              item.product_detail_id === details_id
          );
          if (localItem) {
            localItem.quantity = quantity;
          } else {
            this.localCardItem.push({
              product_id: productId,
              quantity: quantity,
              product_detail_id: details_id,
            });
          }
        }

        await this.fetchCartData();

        this.error = null;
      } catch (error) {
        this.error = error.message;
        console.error("Error updating product quantity:", error.message);
      } finally {
        this.loading = false;
      }
    },

    /*
    |==========================================================
    |===========>        Filtered functions        <=========== 
    |==========================================================
    */
    filterByBrand(brand) {
      this.loading = true;
      this.currentPage = 1;
      try {
        if (brand === "All") {
          this.fetchProducts(1);
          this.products = [...this.originalProducts];
        } else {
          this.products = this.originalProducts.filter(
            (product) => product.vendor && product.brand === brand
          );
        }
        this.error = "";
        this.updatePagination();
      } catch (error) {
        this.error = error.message;
      } finally {
        this.loading = false;
      }
    },

    searchProduct: debounce(async function (query) {
      this.loading = true;
      this.error = null;
      this.noSearchResults = false;

      try {
        const productsFiltered = this.originalProducts.filter((product) =>
          product.product_name.toLowerCase().includes(query.toLowerCase())
        );

        this.products = productsFiltered;

        if (productsFiltered.length === 0) {
          this.noSearchResults = true;
        }
        this.updatePagination();
      } catch (err) {
        this.error = `Failed to search products: ${err.message}`;
        console.error(err);
      } finally {
        this.loading = false;
      }
    }, 300),

    /*
    |==========================================================
    |===========>             Pagination           <=========== 
    |==========================================================
    */
    updatePagination() {
      const totalProducts = this.products.length;
      this.totalPages = Math.ceil(totalProducts / this.itemsPerPage);
      this.currentPage = 1;
      this.products = this.products.slice(0, this.itemsPerPage);
    },

    goToPage(page) {
      if (page < 1 || page > this.totalPages) return;
      this.currentPage = page;
      this.products = this.originalProducts.slice(
        (page - 1) * this.itemsPerPage,
        page * this.itemsPerPage
      );
    },
    /*
    |==========================================================
    |===========>        Favorite Functions        <=========== 
    |==========================================================
    */
    async addToFavorite(product_id) {
      this.loading = true;
      this.error = null;
      const authStore = useAuthStore();
      try {
        if (authStore.isLoggedIn) {
          const response = await axios.post("/api/favorite/", {
            product_id: product_id,
          });
          this.fetchProducts(1);
        } else {
          this.localFavoriteItem.push(product_id);
        }
      } catch (err) {
        this.error = "Failed to fetch cart data";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },

    async removeFavorite(favoriteId) {
      this.loading = true;
      this.error = null;
      const authStore = useAuthStore();
      try {
        if (authStore.isLoggedIn) {
          const response = await axios.delete(`/api/favorite/${favoriteId}`);
        } else {
          const index = this.localFavoriteItem.indexOf(favoriteId);
          if (index !== -1) {
            this.localFavoriteItem.splice(index, 1);
          }
        }
      } catch (err) {
        this.error = "Failed to fetch cart data";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },

    resetFavorites() {
      this.products.forEach((product) => {
        if (product.favorites) {
          product.favorites.length = 0; 
          console.log(
            `Resetting favorites for product ${product.id} to ${product.favorites.length} items`
          );
        } else {
          console.warn(
            `Product ${product.id} does not have a favorites property.`
          );
        }
      });
    },
    /*
    |==========================================================
    |===========>        Favorite Functions        <=========== 
    |==========================================================
    */
    resetProductStore() {
      this.originalProducts = [];
      this.products = [];
      this.cartItems = [];
      this.brands = [];
      this.productDetails = [];
      this.localCardItem = [];
      this.localFavoriteItem = [];
      this.currentPage = 1;
      this.totalPages = null;
      this.loading = false;
      this.error = null;
      this.noSearchResults = false;
      this.itemsPerPage = 30;
      this.resetFavorites();
    },
  },
  persist: true,
});
