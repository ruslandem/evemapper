<template>
  <Navbar />
  <div class="content-block container pb-5">
    <router-view></router-view>
  </div>
  <Footer />
</template>

<style>
@import '@modules/bulma/css/bulma.min.css';
@import '@modules/toastify-js/src/toastify.css';
@import '@modules/tippy.js/dist/tippy.css';
@import '@modules/tippy.js/themes/light.css';
@import '@/../css/bulmaswatch.min.css';
@import '@modules/bulma-extensions/dist/css/bulma-extensions.min.css';
@import '@modules/vue-select/dist/vue-select.css';
@import '@modules/vue-cookie-comply/dist/style.css';
@import '@/../css/app.css';
</style>

<script setup>
import { onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import { glowCookies } from '@/libs/glow-cookies/glowCookies';
import Navbar from '@/components/layout/AppNavbar.vue';
import Footer from '@/components/layout/AppFooter.vue';

useAuthStore().getAuthentication();

onMounted(() => {
  glowCookies.start('en', {
    policyLink: '/privacy',
    hideAfterClick: true
  });
});
</script>
