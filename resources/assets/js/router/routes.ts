
const routes = [
  { path: "/", component: ()=> import("@/components/pages/Home.vue") },
  { path: "/locate", component: ()=> import("@/components/pages/Locate.vue") },
  { path: "/route", component: ()=> import("@/components/pages/Route.vue") },
];

export default routes;
