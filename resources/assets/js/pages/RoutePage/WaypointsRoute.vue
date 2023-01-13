<template>
  <div class="p-5 scrollable h-100" style="border: 1px solid darkslategray">
    <div v-for="(wpRoute, wpIndex) in route" :key="wpIndex">
      <waypoints-route-table :route="wpRoute" />
    </div>
  </div>
</template>

<script setup lang="ts">
import WaypointsRouteTable from './WaypointsRouteTable.vue';
import { SolarSystem } from '@/structures/solar-system';
import { getSecurityStatusStyle } from '@/services/utils';

interface Props {
  route: SolarSystem[][];
}

defineProps<Props>();
</script>
