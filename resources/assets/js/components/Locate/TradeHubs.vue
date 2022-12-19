<template>
  <div class="is-dark-half p-4 my-1 has-text-centered">
    <h5 class="title">Nearest Trade Hubs</h5>
    <span
      v-for="(value, index) in jumps"
      :key="index"
      class="tag is-medium is-info m-1"
    >
      {{ index }}: {{ value }}
      <a
        :href="`/route?waypoints=${systemName},${index}`"
        class="has-text-warning"
        ><i class="fa-solid fa-route ml-1"></i
      ></a>
    </span>
  </div>
</template>

<script setup lang="ts">
import { HubsJump } from "@/structures/HubsJump";
import { defineProps } from "vue";

const props = defineProps({
  jumps: {} as HubsJump,
  systemName: String,
});
</script>
