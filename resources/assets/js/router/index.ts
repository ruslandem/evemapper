import { createRouter, createWebHistory } from "vue-router";
import routes from "./routes";
import { trackRouter } from "vue-gtag-next";

const router = createRouter({
  history: createWebHistory(),
  routes,
});

trackRouter(router);

export default router;