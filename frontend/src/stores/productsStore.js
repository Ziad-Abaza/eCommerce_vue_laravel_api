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
      const userAuth = useAuthStore().isLoggedIn;
      return userAuth ? state.cartItems.length : state.localCardItem.length;
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
      console.log("Start fetching cart data...");

      const userAuth = useAuthStore().isLoggedIn;
      console.log("User Auth Status:", userAuth);

      try {
        if (userAuth) {
          const response = await axios.get("/api/cart/");
          console.log("API Response for Cart Data:", response.data);

          this.cartItems = response.data.data;
          console.log("Cart Items:", this.cartItems);

          this.findBrands();
        }
      } catch (err) {
        this.error = "Failed to fetch cart data";
        console.error("Error in fetchCartData:", err);
      } finally {
        this.loading = false;
        console.log("Finished fetching cart data. Loading:", this.loading);
      }
    },

    async addToCart(productId, quantity, product_detail_id) {
      this.loading = true;
      this.error = null;
      console.log(
        `Adding product to cart: Product ID: ${productId}, Quantity: ${quantity}, Detail ID: ${product_detail_id}`
      );

      const authStore = useAuthStore();
      const notificationStore = useNotificationStore();
      console.log("User Auth Status:", authStore.isLoggedIn);

      try {
        if (authStore.isLoggedIn) {
          const product = this.originalProducts.find(
            (product) => product.id === productId
          );
          console.log("Found Product:", product);

          if (!product) {
            throw new Error("Product not found");
          }
          const response = await axios.post("/api/cart/", {
            product_id: productId,
            quantity: quantity,
            product_detail_id: product_detail_id,
          });
          console.log("API Response for Add to Cart:", response.data);
        } else {
          const product = this.originalProducts.find(
            (product) => product.id === productId
          );
          console.log("Found Product (Local Cart):", product);

          if (!product) {
            throw new Error("Product not found");
          }

          this.localCardItem.push({
            product: { ...product },
            product_id: productId,
            quantity: quantity,
            product_detail_id: product_detail_id,
          });
          console.log("Added to Local Cart:", this.localCardItem);
        }

        await this.fetchCartData();
        notificationStore.setNotifications("Product added to Cart");
      } catch (err) {
        this.error = "Failed to add item to cart";
        console.error("Error in addToCart:", err);
      } finally {
        this.loading = false;
        console.log("Finished adding product to cart. Loading:", this.loading);
      }
    },

    async removeFromCart(cartId, productId) {
      this.loading = true;
      this.error = null;
      console.log(
        `Removing item from cart: Cart ID: ${cartId}, Product ID: ${productId}`
      );

      const authStore = useAuthStore();
      console.log("User Auth Status:", authStore.isLoggedIn);

      try {
        if (authStore.isLoggedIn) {
          const product = this.originalProducts.find(
            (product) => product.id == productId
          );
          console.log("product id:", productId);
          console.log("Found Product:", product);

          if (!product) {
            throw new Error("Product not found");
          }

          const response = await axios.delete(`/api/cart/${cartId}`);
          console.log("API Response for Remove from Cart:", response.data);
        } else {
          const index = this.localCardItem.findIndex(
            (item) => item.product_id === productId
          );
          console.log("Found Item Index in Local Cart:", index);

          if (index !== -1) {
            this.localCardItem.splice(index, 1);
            console.log("Removed Item from Local Cart:", this.localCardItem);
          }
        }

        await this.fetchCartData();
      } catch (err) {
        this.error = "Failed to delete item from cart data";
        console.error("Error in removeFromCart:", err);
      } finally {
        this.loading = false;
        console.log(
          "Finished removing product from cart. Loading:",
          this.loading
        );
      }
    },

    async updateProductQuantity(cartId, productId, quantity, details_id) {
      if (this.loading) return;
      this.loading = true;
      this.error = null;
      console.log(
        ` Cart ID: ${cartId}, Product ID: ${productId}, Quantity: ${quantity}, Details ID: ${details_id}`
      );

      try {
        const authStore = useAuthStore();
        const userAuth = authStore.isLoggedIn;
        console.log("User Auth Status:", userAuth);

        if (userAuth) {
          const response = await axios.post(`/api/cart/${cartId}`, {
            product_id: productId,
            quantity: quantity,
            product_detail_id: details_id,
          });
          console.log("API Response:", response.data);
        } else {
          const localItem = this.localCardItem.find(
            (item) =>
              item.product_id == productId &&
              item.product_detail_id == details_id
          );
          console.log(" Updated:", localItem);

          if (localItem) {
            localItem.quantity = quantity;
            console.log("Updated Quantity successfully:", localItem);
          } else {
            this.localCardItem.push({
              product_id: productId,
              quantity: quantity,
              product_detail_id: details_id,
            });
            console.log("Added New Item to Local Cart:", this.localCardItem);
          }
        }

        await this.fetchCartData();
        console.log("Successfully updated product quantity.");
        this.error = null;
      } catch (error) {
        this.error = error.message;
        console.error("Error updating product quantity:", error.message);
      } finally {
        this.loading = false;
        console.log(
          "Finished updating product quantity. Loading:",
          this.loading
        );
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

    async removeFavorite(favoriteId, productID = null) {
      this.loading = true;
      this.error = null;
      const authStore = useAuthStore();
      try {
        if (authStore.isLoggedIn) {
          const response = await axios.delete(`/api/favorite/${favoriteId}`);
        } else {
          const index = this.localFavoriteItem.indexOf(productID);
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
    /*
    |==========================================================
    |===========>        Favorite Functions        <=========== 
    |==========================================================
    */
    resetProductStore() {
      console.log("Resetting originalProducts...");
      this.originalProducts = [];
      console.log(this.originalProducts);

      console.log("Resetting products...");
      this.products = [];
      console.log(this.products);

      console.log("Resetting cartItems...");
      this.cartItems = [];
      console.log(this.cartItems);

      console.log("Resetting brands...");
      this.brands = [];
      console.log(this.brands);

      console.log("Resetting productDetails...");
      this.productDetails = [];
      console.log(this.productDetails);

      console.log("Resetting localCardItem...");
      this.localCardItem = [];
      console.log(this.localCardItem);

      console.log("Resetting localFavoriteItem...");
      this.localFavoriteItem = [];
      console.log(this.localFavoriteItem);

      console.log("Resetting currentPage to 1...");
      this.currentPage = 1;

      console.log("Resetting totalPages to null...");
      this.totalPages = null;

      console.log("Resetting loading to false...");
      this.loading = false;

      console.log("Resetting error to null...");
      this.error = null;

      console.log("Resetting noSearchResults to false...");
      this.noSearchResults = false;

      console.log("Resetting itemsPerPage to 30...");
      this.itemsPerPage = 30;

      console.log("Fetching products after reset...");
      this.fetchProducts();

      console.log("Product store reset completed.");
    },
  },
  persist: true,
});
