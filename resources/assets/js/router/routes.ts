import { useAuthStore } from "@/stores/auth";
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
  const auth = useAuthStore();
  if (!auth.isAuthenticated) {
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
  {
    name: "privacy",
    path: "/privacy",
    component: () => import("@/components/Misc/Privacy.vue"),
  },
  {
    name: "legal",
    path: "/legal",
    component: () => import("@/components/Misc/Legal.vue"),
  },
  {
    name: "contacts",
    path: "/contacts",
    component: () => import("@/components/Misc/Contacts.vue"),
  },
];

export default routes;
