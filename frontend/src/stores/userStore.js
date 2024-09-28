import { defineStore } from "pinia";
import axios from "axios";
import { useAuthStore } from "@/stores/authStore";
import { useNotificationStore } from "@/stores/notificationStore";

export const useUserStore = defineStore("user", {
  state: () => ({
    userInfo: null,
    orders: [],
    purchases: [],
    favorites: [],
    loading: false,
    error: null,
  }),
  getters: {
    getUserData(state) {
      return state.userInfo;
    },
    getOrderData(state) {
      return state.orders;
    },
    getPurchaseData(state) {
      return state.purchases;
    },
    getUserFavorite(state) {
      return state.favorites;
    },
  },
  actions: {
    /*
    |-------------------------------------------------
    | Fetch user profile and related data
    |-------------------------------------------------
    */
    async fetchUsers() {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get(`/api/profile`);
        const data = response.data.data;
        /********************* Set user information *********************/
        this.userInfo = {
          id: data.id,
          name: data.name,
          email: data.email,
          phone: data.phone,
          address: data.address,
          isActive: data.is_active,
        };
        /********************* Set order details *********************/

        this.orders = data.orders.map((order) => ({
          id: order.id,
          shipping_cost: order.shipping_cost,
          shipping_address: order.shipping_address,
          order_status: order.order_status,
          total_amount: order.total_amount,
        }));
        /********************* Set purchase details *********************/

        this.purchases = data.purchases.map((purchase) => ({
          id: purchase.id,
          quantity: purchase.quantity,
          total_price: purchase.total_price,
          purchase_date: purchase.purchase_date,
        }));
        /********************* Set favorite products *********************/

        this.favorites = data.favorites.map((favorite) => ({
          id: favorite.id,
          product_name: favorite.product_name,
          description: favorite.description,
          brand: favorite.brand,
        }));
      } catch (err) {
        this.error = "Failed to fetch profile";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    /*
    |-------------------------------------------------
    | Update user information
    |-------------------------------------------------
    */
    async updateUser(userData) {
      this.loading = true;
      this.error = null;
      const notificationStore = useNotificationStore();
      try {
        const response = await axios.post(`/api/profile/${userData.id}`, {
          name: userData.name,
          email: userData.email,
          phone: userData.phone,
          address: userData.address,
        });
        /********************* Update user information in the store *********************/

        this.userInfo = {
          ...this.userInfo,
          name: response.data.name,
          email: response.data.email,
          phone: response.data.phone,
          address: response.data.address,
        };
        this.fetchUsers();
        notificationStore.setNotifications('Profile updated successfully','s uccess');

        console.log("User updated successfully:", response.data);
      } catch (err) {
        this.error = "Failed to update user information";
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
  },
  persist: true,
});
