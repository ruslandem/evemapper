<template>
  <div class="has-text-white">
    <SearchBar @updateSolarSystem="updateSolarSystem" />
    <SystemInfo :solarSystem="solarSystem" />
  </div>
</template>

<script setup>
import { ref } from "vue";
import SearchBar from "./SearchBar.vue";
import SystemInfo from "./SystemInfo.vue";

const solarSystem = ref("");

const updateSolarSystem = (systemSearch) => {
  solarSystem.value = systemSearch;
};
</script>
