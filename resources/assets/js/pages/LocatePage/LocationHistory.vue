<template>
  <!-- History -->
  <div class="is-dark-1 p-4 my-1 has-text-centered h-100">
    <h5 class="title">Locations History</h5>

    <div v-if="isLoading">
      <progress
        class="progress is-primary is-radiusless"
        style="height: 0.5rem"
      ></progress>
    </div>

    <div v-if="!isLoading" class="scrollable mb-5">
      <table class="table is-fullwidth is-bordered is-size-7">
        <thead>
          <tr class="has-background-dark">
            <th>System</th>
            <th>Security</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(location, index) in locations" :key="index">
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

<style scoped>
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
import { ref, watch } from 'vue';
import { VisitedLocation } from '@/structures/visited-location';
import { getSecurityStatusStyle } from '@/services/utils';
import { getRelativeTime } from '@/services/utils';

interface Props {
  locations?: VisitedLocation[];
}

const props = defineProps<Props>();

/**
 * Progress Bar handle
 */
const isLoading = ref(false);
watch(props, (value: Readonly<Props>): void => {
  isLoading.value = value.locations?.length == 0;
});
</script>
