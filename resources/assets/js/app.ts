import { createApp } from 'vue';
import router from '@/router';
import App from './App.vue';
import vSelect from 'vue-select';
import VueGtag from 'vue-gtag-next';
import { createPinia } from 'pinia';
import { appConfig } from './config';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import '@/libs/icons';

// Use google analytics only if cookie consent was accepted
const gTagProperty: object =
  localStorage.getItem('GlowCookies') === '1' ? { id: appConfig.gTagId } : { id: null };

createApp(App)
  .use(createPinia())
  .use(router)
  .use(VueGtag, { property: gTagProperty })
  .component('fa-icon', FontAwesomeIcon)
  .component('v-select', vSelect)
  .mount('#app');
