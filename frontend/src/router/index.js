import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store";

// Views
import LoginView from "../views/Login.vue";
import DashboardView from "../views/Dashboard.vue";
import TaskForm from "../views/TaskForm.vue";
import NotFound from "../components/NotFound.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    redirect: "/dashboard",
  },
  {
    path: "/login",
    name: "Login",
    component: LoginView,
    meta: { requiresGuest: true },
  },

  {
    path: "/dashboard",
    name: "Dashboard",
    component: DashboardView,
    meta: { requiresAuth: true },
  },
  {
    path: "/tasks/create",
    name: "CreateTask",
    component: TaskForm,
    meta: { requiresAuth: true },
  },
  {
    path: "/tasks/:id/edit",
    name: "EditTask",
    component: TaskForm,
    meta: { requiresAuth: true },
    props: true,
  },
  {
    path: "*",
    name: "notfound",
    component: NotFound, // catch-all para páginas inválidas
  },
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

// Navigation Guards
router.beforeEach((to, from, next) => {
  const isAuthenticated = store.getters["auth/isAuthenticated"];

  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next("/login");
    } else {
      next();
    }
  } else if (to.matched.some((record) => record.meta.requiresGuest)) {
    if (isAuthenticated) {
      next("/dashboard");
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
