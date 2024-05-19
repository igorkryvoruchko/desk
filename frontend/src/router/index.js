import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import SignInView from "@/views/SignInView.vue";
import SignUpView from "@/views/SignUpView.vue";

const routes = [
  {
    path: "/",
    name: "Desc Home",
    component: HomeView,
  },
  {
    path: "/sign-in",
    name: "Desc Sign In",
    component: SignInView,
  },
  {
    path: "/sign-up",
    name: "Desc Sign Up",
    component: SignUpView,
  },
  {
    path: "/about",
    name: "Desc Login",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/AboutView.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});
router.beforeEach((to, from, next) => {
  document.title = to.name;
  next();
});
export default router;
