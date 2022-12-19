<template>
  <div class="is-dark-half p-4 my-1 has-text-centered">
    <h5 class="title">Locations History</h5>

    <table class="table is-fullwidth is-bordered is-size-7">
      <thead>
        <tr class="has-background-dark">
          <th>System</th>
          <th>Security</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="location in locations">
            <td>{{ location.solarSystemName }}</td>
            <td :style="[getSecurityStatusStyle(location.solarSystemSecurity)]">{{ location.solarSystemSecurity.toFixed(2) }}</td>
            <td>{{ location.createdAt }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { VisitedLocation } from "@/structures/VisitedLocation";
import { defineProps } from "vue";
import { getSecurityStatusStyle } from "@/services/utils";

const props = defineProps({
  locations: Array<VisitedLocation>,
});
</script>
