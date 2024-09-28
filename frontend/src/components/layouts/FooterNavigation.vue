<template>
  <section class="footer-navigation">
    <router-link :to="{ name: 'home' }"
      ><i class="fas fa-home nav-btn"></i
    ></router-link>
    <router-link :to="{ name: 'cart' }"
      ><i class="fas fa-shopping-cart nav-btn"></i
    ></router-link>

    <!-- Notification Bell -->
    <div class="notification-wrapper">
      <i class="fas fa-bell nav-btn" @click="toggleNotificationBox"></i>

      <!-- Popover for new notification with animation -->
      <transition name="slide-fade">
        <div
          v-if="showPopover"
          class="notification-popover"
          :style="{ border: '2px solid ' + notificationColor }"
        >
          <i
            :class="notificationIcon"
            :style="{ color: notificationColor }"
          ></i>
          {{ latestNotification.message }}
        </div>
      </transition>

      <!-- Notification Box with max-height and smooth scrolling -->
      <transition name="fade">
        <div v-if="showNotificationBox" class="notification-box">
          <div v-if="notifications.length > 0">
            <div
              v-for="(notification, index) in notifications"
              :key="index"
              class="notification-item"
            >
              <span :style="{ color: getColor(notification.type) }">
                <i
                  :class="getIcon(notification.type)"
                  :style="{ color: getColor(notification.type) }"
                ></i>
                {{ notification.message }}
              </span>
              <button
                class="delete-btn"
                @click="removeNotification(notification.id,index)"
              >
                ✖
              </button>
            </div>

            <div class="notification-footer">
              <button class="clear-btn" @click="clearNotifications">
                Clear All
              </button>
            </div>
          </div>
          <div v-else>
            <div class="no-notifications">No notifications available.</div>
          </div>
        </div>
      </transition>
    </div>

    <router-link :to="{ name: 'profile' }"
      ><i class="fas fa-user nav-btn"></i
    ></router-link>
  </section>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { useNotificationStore } from "@/stores/notificationStore";

const notificationStore = useNotificationStore();

/*************** Notifications Array ***************/
const notifications = ref([]);
const latestNotification = ref(null); // latest notification as a single object
const showPopover = ref(false);
const showNotificationBox = ref(false);

/*************** Notification Style ***************/
const notificationColor = ref("#333");
const notificationIcon = ref("fas fa-bell");

let popoverTimeout = null;

/*
|-----------------------------------------------
| Function to show and add new notification
|-----------------------------------------------
*/
function setTempNotification(notification) {
  latestNotification.value = notification;
  updateNotificationStyle(notification.type);
  showPopover.value = true;

  // Add the notification to the notifications array
  notifications.value.push(notification);

  // Reset timeout if new notification comes in
  if (popoverTimeout) clearTimeout(popoverTimeout);

  // Hide the popover after 3 seconds
  popoverTimeout = setTimeout(() => {
    showPopover.value = false;
    latestNotification.value = null;
  }, 3000);
}

/*
|-----------------------------------------------
| Watch for new notifications from the store
|-----------------------------------------------
*/
watch(
  () => notificationStore.latestNotification,
  (newNotification) => {
    if (newNotification) {
      setTempNotification(newNotification);
    }
  }
);

/*
|-----------------------------------------------
| Function to toggle the notification box
|-----------------------------------------------
*/
function toggleNotificationBox() {
  showNotificationBox.value = !showNotificationBox.value;
}

/*
|-----------------------------------------------
| Function to remove a specific notification
|-----------------------------------------------
*/
/*
|-----------------------------------------------
| Function to remove a specific notification
|----------------------------------------------- 
*/
async function removeNotification(id, index) {
  await notificationStore.deleteNotification(id);
  notifications.value.splice(index, 1); 
}

/*
|-----------------------------------------------
| Function to clear all notifications
|----------------------------------------------- 
*/
async function clearNotifications() {
  await notificationStore.clearAllNotifications(); 
  notifications.value = [];
  toggleNotificationBox();
}


/*
|-----------------------------------------------
| Function to get icon class based on notification type
|-----------------------------------------------
*/
function getIcon(type) {
  switch (type) {
    case "error":
      return "fas fa-exclamation-circle";
    case "info":
      return "fas fa-info-circle";
    case "warning":
      return "fas fa-exclamation-triangle";
    case "success":
      return "fas fa-check-circle";
    default:
      return "fas fa-bell";
  }
}

/*
|-----------------------------------------------
| Function to get color based on notification type
|-----------------------------------------------
*/
function getColor(type) {
  switch (type) {
    case "error":
      return "red";
    case "info":
      return "#007bff";
    case "warning":
      return "#ffc107";
    case "success":
      return "green";
    default:
      return "#333";
  }
}

/*
|-----------------------------------------------
| Function to update notification icon and color
|-----------------------------------------------
*/
function updateNotificationStyle(type) {
  notificationIcon.value = getIcon(type);
  notificationColor.value = getColor(type);
}

/*
|-----------------------------------------------
| On mounted lifecycle hook to initialize notifications
|-----------------------------------------------
*/
onMounted(async () => {
  await notificationStore.fetchNotifications();
  notifications.value = notificationStore.getNotifications;
});
</script>

<style scoped>
.footer-navigation {
  width: 100%;
  display: flex;
  justify-content: space-around;
  padding: 10px 0;
  background-color: #ffffff;
  border-top: 1px solid #ddd;
  position: fixed;
  bottom: 0;
  right: 0;
  z-index: 999999;
}

.nav-btn {
  background: none;
  border: none;
  font-size: 24px;
  color: #333;
  cursor: pointer;
  transition: color 0.3s;
}

.nav-btn:hover {
  color: #007bff;
}

/* Popover style with sliding animation */
.notification-popover {
  position: absolute;
  bottom: 50px;
  right: 0;
  background-color: #f0f0f0;
  padding: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  display: flex;
  align-items: center;
  gap: 10px;
}

.notification-popover i {
  color: #007bff;
}

/* Notification box style with max-height */
.notification-box {
  position: absolute;
  bottom: 60px;
  right: 0;
  width: 250px;
  background-color: #ffffff;
  border: 1px solid #ddd;
  border-radius: 5px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  padding: 10px;
  max-height: 300px;
  overflow-y: auto;
}

.notification-item {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  padding: 5px;
  background-color: #f9f9f9;
  border-radius: 5px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  align-items: center;
}

.notification-item span {
  display: flex;
  align-items: center;
  gap: 10px;
}

.delete-btn {
  background: none;
  border: none;
  color: red;
  font-size: 14px;
  cursor: pointer;
}

.delete-btn:hover {
  color: darkred;
}

.clear-btn {
  width: 100%;
  background-color: #f44336;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  margin-top: 10px;
}

.clear-btn:hover {
  background-color: #e53935;
}

/* Animations */
@keyframes slide {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.slide-fade-enter-active {
  transition: all 0.5s ease;
}

.slide-fade-leave-active {
  transition: all 0.5s ease;
  opacity: 0;
  transform: translateY(10px);
}
</style>
