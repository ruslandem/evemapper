import { createApp } from "vue";
import router from "@/router";
import App from "./App.vue";
import vSelect from "vue-select";
import VueGtag from "vue-gtag";
import { createPinia } from "pinia";
// FontAwesome
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { far } from "@fortawesome/free-regular-svg-icons";
import { dom } from "@fortawesome/fontawesome-svg-core";

library.add(fas);
library.add(far);
dom.watch();

createApp(App)
  .use(createPinia())
  .use(router)
  .use(VueGtag, {
    config: {
      id: "G-GPRF1TNFPB",
    },
  })
  .component("font-awesome-icon", FontAwesomeIcon)
  .component("v-select", vSelect)
  .mount("#app");
