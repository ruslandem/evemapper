<script>
import Navbar from "@/components/ui/Navbar.vue";
import Footer from "@/components/ui/Footer.vue";
export default {
  components: {
    Navbar,
    Footer,
  },
};
</script>


<template>
  <Navbar />
  <div class="container" style="min-height: 20rem">
    <router-view></router-view>
  </div>
  <Footer />
</template>
