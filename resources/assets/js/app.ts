import { createApp } from 'vue';
import router from '@/router';
import App from './App.vue';
import vSelect from 'vue-select';
import VueGtag from 'vue-gtag-next';
import { createPinia } from 'pinia';
import { gTagId } from './config';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import '@/libs/icons';

createApp(App)
  .use(createPinia())
  .use(router)
  .use(VueGtag, { property: { id: gTagId() } })
  .component('fa-icon', FontAwesomeIcon)
  .component('v-select', vSelect)
  .mount('#app');
