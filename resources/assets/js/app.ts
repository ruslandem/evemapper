import { createApp } from "vue";
import router from "@/router";
import App from "./App.vue";
import vSelect from "vue-select";
import VueGtag from "vue-gtag-next";
import { createPinia } from "pinia";
// FontAwesome
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import "@/libs/icons";

createApp(App)
  .use(createPinia())
  .use(router)
  .use(VueGtag, {
    property: {
      id: localStorage.getItem("GlowCookies") === "1" ? "G-GPRF1TNFPB" : null,
    },
  })
  .component("fa-icon", FontAwesomeIcon)
  .component("v-select", vSelect)
  .mount("#app");
