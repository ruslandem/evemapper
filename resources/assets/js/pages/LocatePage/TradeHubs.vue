<template>
  <div class="is-dark-1 p-4 my-1 has-text-centered">
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
        ><fa-icon icon="fas fa-route" class="ml-1"
      /></a>
    </span>
  </div>
</template>

<script setup lang="ts">
import { HubsJump } from '@/structures/hubj-jump';

defineProps({
  jumps: {} as HubsJump,
  systemName: String
});
</script>
