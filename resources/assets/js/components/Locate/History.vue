<template>
  <!-- History -->
  <div class="is-dark-1 p-4 my-1 has-text-centered" style="height: 100%">
    <h5 class="title">Locations History</h5>

    <div class="scrollable">
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
            <td>
              <a
                href="#"
                @click.prevent="
                  $emit(
                    'updateSystem',
                    ($event.target as HTMLElement).innerText
                  )
                "
                :style="[getSecurityStatusStyle(location.solarSystemSecurity)]"
              >
                {{ location.solarSystemName }}
              </a>
            </td>
            <td :style="[getSecurityStatusStyle(location.solarSystemSecurity)]">
              {{ location.solarSystemSecurity.toFixed(2) }}
            </td>
            <td>
              <span :title="location.createdAt.toString()">{{
                getRelativeTime(location.createdAt.toString())
              }}</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- /History -->
</template>

<style>
.main {
  border: 1px solid red;
  height: 100%;
}
.table-wrapper {
  border: 1px solid yellow;
  overflow-y: auto;
  height: 100%;
}
</style>

<script setup lang="ts">
import { VisitedLocation } from "@/structures/VisitedLocation";
import { defineProps } from "vue";
import { getSecurityStatusStyle } from "@/services/utils";
import { getRelativeTime } from "@/services/utils";

defineProps({
  locations: Array<VisitedLocation>,
});
</script>
