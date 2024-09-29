import axios from "axios";
import { defineStore } from "pinia";
import { useAuthStore } from "@/stores/authStore";

export const useNotificationStore = defineStore("notifications", {
  state: () => ({
    notifications: [],
    latestNotification: null,
    loading: false,
    error: null,
  }),
  getters: {
    getNotifications(state) {
      return state.notifications;
    },
    getLatestNotification(state) {
      return state.latestNotification;
    },
  },
  actions: {
    /*
    |-------------------------------------------------
    | Fetch notifications from the API
    |-------------------------------------------------
    */
    async fetchNotifications() {
      this.loading = true;
      this.error = null;

      try {
        const userAuth = useAuthStore().isLoggedIn;
        if (userAuth) {
          const response = await axios.get("/api/notification/");
          if (response.data && response.data.data) {
            this.notifications = response.data.data;
            this.latestNotification = this.notifications[0] || null;
          }
        }
      } catch (err) {
        this.error = "Failed to fetch notifications";
        console.error("Error fetching notifications:", err);
      } finally {
        this.loading = false;
      }
    },

    /*
    |-------------------------------------------------
    | Delete a specific notification by ID
    |-------------------------------------------------
    */
    async deleteNotification(id) {
      this.loading = true;
      this.error = null;

      try {
        await axios.delete(`/api/notification/${id}`);
        this.notifications = this.notifications.filter(
          (notification) => notification.id !== id
        );
        this.latestNotification = this.notifications[0] || null;
      } catch (err) {
        this.error = "Failed to delete notification";
        console.error("Error deleting notification:", err);
      } finally {
        this.loading = false;
      }
    },

    /*
    |-------------------------------------------------
    | Clear all notifications for the authenticated user
    |-------------------------------------------------
    */
    async clearAllNotifications() {
      this.loading = true;
      this.error = null;
      const userAuth = useAuthStore().isLoggedIn;

      try {
        if (userAuth) {
          await axios.delete("/api/notification/clear-all/");

          this.notifications = [];
          this.latestNotification = null;

          console.log("Notifications cleared successfully.");
        } else {
          this.error = "User is not authenticated.";
          console.error("User is not logged in.");
        }
      } catch (err) {
        this.error = "Failed to clear notifications";
        console.error("Error clearing notifications:", err);
      } finally {
        this.loading = false;
      }
    },

    /*
    |-------------------------------------------------
    | Add a new notification
    |-------------------------------------------------
    */
    setNotifications(message, type = "info") {
      const notification = { message, type };
      this.notifications.push(notification);
      this.latestNotification = notification;
    },
  },

  persist: true,
});
