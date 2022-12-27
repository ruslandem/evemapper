import { useAuthStore } from "@/stores/auth";
import {
  NavigationGuardNext,
  RouteLocationNormalized,
  RouteRecordRaw,
} from "vue-router";

const admins: string[] = ["Khazad Tyori"];

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

const checkAdmin = (
  to: RouteLocationNormalized,
  from: RouteLocationNormalized,
  next: NavigationGuardNext
) => {
  const auth = useAuthStore();
  if (!auth.isAuthenticated) {
    next("login");
    return false;
  }
  if (auth.character && !admins.includes(auth.character.name)) {
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
  {
    name: "admin",
    path: "/admin",
    beforeEnter: checkAdmin,
    component: () => import("@/components/Admin/Index.vue"),
  },
];

export default routes;
