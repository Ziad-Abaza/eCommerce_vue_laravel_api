import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import DetailsProduct from "../views/DetailsProduct.vue";
import CartShopping from "../views/CartShopping.vue";
import DashBoard from "../views/DashBoard.vue";
import UserProfile from "../views/UserProfile.vue";
import Login from "../components/auth/Login.vue";
import Register from "../components/auth/Register.vue";

const routes = [
  {
    path: "/",
    name: "home",
    component: HomeView,
  },
  {
    path: "/product/details/:id",
    name: "details",
    component: DetailsProduct,
  },
  {
    path: "/cart",
    name: "cart",
    component: CartShopping,
  },
  {
    path: "/profile",
    name: "profile",
    component: UserProfile,
  },
  {
    path: "/logout",
    name: "logout",
    component: Register,
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/register",
    name: "Register",
    component: Register,
  },
  {
    path: "/dashboard",
    name: "dashboard",
    component: DashBoard,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
