import { isAuthenticated } from "@/services/auth";
import {
  NavigationGuardNext,
  RouteLocationNormalized,
  RouteRecordRaw,
} from "vue-router";

const checkAuth = (
  to: RouteLocationNormalized,
  from: RouteLocationNormalized,
  next: NavigationGuardNext
) => {
  if (!isAuthenticated()) {
    next("login");
    return false;
  }
  next();
};

const routes: RouteRecordRaw[] = [
  {
    path: "/",
    component: () => import("@/components/Home/Index.vue"),
  },
  {
    path: "/locate",
    component: () => import("@/components/Locate/Index.vue"),
    beforeEnter: checkAuth,
  },
  {
    path: "/route",
    component: () => import("@/components/Route/Index.vue"),
    beforeEnter: checkAuth,
  },
  {
    name: "login",
    path: "/login",
    component: () => import("@/components/Auth/Login.vue"),
  },
];

export default routes;
