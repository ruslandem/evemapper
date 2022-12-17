import { RouteRecordRaw } from "vue-router";

const routes: RouteRecordRaw[] = [
  { path: "/", component: () => import("@/components/Home/Index.vue") },
  { path: "/locate", component: () => import("@/components/Locate/Index.vue") },
  { path: "/route", component: () => import("@/components/Route/Index.vue") },
];

export default routes;
