import axios from "axios";
import Cookies from "js-cookie";
import { defineStore } from "pinia";
import { useProductsStore } from "./productsStore";
import { useUserStore } from "./userStore";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    currentUser: null,
    error: null,
    userId: null,
  }),

  getters: {
    isLoggedIn: (state) => !!state.currentUser,
    getUserDetails: (state) => state.currentUser,
    getUser: (state) => state.userId,
    getError: (state) => state.error,
  },

  actions: {
    /*
    |-------------------------------------------------
    | Fetch CSRF token for secure requests
    |-------------------------------------------------
    */
    async getCsrfToken() {
      await axios.get("/sanctum/csrf-cookie");
    },

    /*
    |-------------------------------------------------
    | Handle user registration and set authentication data
    |-------------------------------------------------
    */
    async register(form) {
      try {
        // Fetch CSRF token
        await this.getCsrfToken();

        await axios.post("api/register", {
          name: form.name,
          email: form.email,
          phone: form.phone,
          address: form.address,
          password: form.password,
          password_confirmation: form.password_confirmation,
        });

        await this.fetchCurrentUser();
        return true;
      } catch (error) {
        this.handleError(error);
        return false;
      }
    },

    /*
    |-------------------------------------------------
    | Handle user login and set authentication data
    |-------------------------------------------------
    */
    async handleLogin(form) {
      try {
        await this.getCsrfToken();
        const response = await axios.post("api/login", {
          email: form.email,
          password: form.password,
        });

        await this.fetchCurrentUser();

        if (this.currentUser) {
          const isSecure = window.location.protocol === "https:";
          Cookies.set("authUser", JSON.stringify(this.currentUser), {
            secure: isSecure,
            sameSite: "Strict",
          });

          await this.mergeLocalDataWithAccount();
          return true;
        } else {
          throw new Error("Failed to retrieve user data. Please try again.");
        }
      } catch (error) {
        this.handleError(error);
        return false;
      }
    },

    /*
    |-------------------------------------------------
    | Fetch the authenticated user's data
    |-------------------------------------------------
    */
    async fetchCurrentUser() {
      try {
        await this.getCsrfToken();
        const response = await axios.get("/api/user");
        this.currentUser = response.data;
        this.userId = response.data.id;

        const isSecure = window.location.protocol === "https:";
        Cookies.set("authUser", JSON.stringify(this.currentUser), {
          secure: isSecure,
          sameSite: "Strict",
        });
      } catch (error) {
        this.resetAuthState();
        this.handleError(error);
      }
    },

    /*
    |-------------------------------------------------
    | Logout the user and remove cookies
    |-------------------------------------------------
    */
    async logout() {
      try {
        await axios.post("/api/logout");
        this.resetAuthState();
        Cookies.remove("authUser");
        const productStore = useProductsStore();
        productStore.resetProductStore();
        const userStore = useUserStore();
        userStore.resetUserStore(); 
      } catch (error) {
        this.handleError(error);
      }
    },

    /*
    |-------------------------------------------------
    | Merge local data with the user's account after login
    |-------------------------------------------------
    */
    async mergeLocalDataWithAccount() {
      const productStore = useProductsStore();
      const localCartData = productStore.getLocalCardItem;
      const localFavoriteData = productStore.getLocalFavoriteItem;

      try {
        if (localCartData.length > 0) {
          await Promise.all(
            localCartData.map(async (item) => {
              await axios.post("/api/cart", {
                product_id: item.product_id,
                quantity: item.quantity,
                product_detail_id: item.product_detail_id,
              });
            })
          );
          productStore.localCardItem = [];
        }

        if (localFavoriteData.length > 0) {
          await Promise.all(
            localFavoriteData.map(async (product_id) => {
              await axios.post("/api/favorite", { product_id });
            })
          );
          productStore.localFavoriteItem = [];
        }

        await productStore.fetchCartData();
        await productStore.fetchProducts(1);
      } catch (error) {
        console.error("Failed to merge local data:", error);
      }
    },

    /*
    |-------------------------------------------------
    | Handle error responses and update the state
    |-------------------------------------------------
    */
    handleError(error) {
      if (error.response) {
        console.error("Response data:", error.response.data);
        console.error("Response status:", error.response.status);
        console.error("Response headers:", error.response.headers);
        this.error =
          error.response.data.message ||
          "An error occurred during the operation.";
      } else if (error.request) {
        console.error("Request data:", error.request);
        this.error = "No response received from the server.";
      } else {
        console.error("Error message:", error.message);
        this.error = "An unexpected error occurred.";
      }
    },

    /*
    |-------------------------------------------------
    | Reset authentication state to default
    |-------------------------------------------------
    */
    resetAuthState() {
      this.currentUser = null;
      this.userId = null;
      this.error = null;
      Cookies.remove("authUser");
    },
  },

  persist: true,
});
