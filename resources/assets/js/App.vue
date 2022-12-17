<template>
  <Navbar />
  <div class="container">
    <router-view></router-view>
  </div>
  <Footer />
</template>

<style>
@import "@modules/bulma/css/bulma.min.css";
@import "@modules/toastify-js/src/toastify.css";
@import "@modules/tippy.js/dist/tippy.css";
@import "@modules/tippy.js/themes/light.css";
@import "@modules/vanilla-cookieconsent/dist/cookieconsent.css";
@import "@/../css/bulmaswatch.min.css";
@import "@/../css/app.css";
</style>

<script setup>
import Navbar from "@/components/ui/Navbar.vue";
import Footer from "@/components/ui/Footer.vue";
</script>
