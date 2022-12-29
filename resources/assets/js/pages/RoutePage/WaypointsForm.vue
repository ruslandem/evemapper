<template>
  <!-- WaypointsForm -->
  <div class="waypoints-form is-dark-1 p-4 my-1">
    <p class="title is-3 has-text-white">Route</p>
    <p class="subtitle is-6 has-text-white">
      Get optimal shortest routing from origin to destination through the set of
      waypoints:
    </p>

    <div id="searchBar" class="field has-addons">
      <p class="control w-100">
        <span class="select w-100">
          <v-select
            v-model="selectedSolarSystem"
            @search="searchSolarSystemsList"
            :options="searchSolarSystemsOptions"
            :value="{ name: selectedSolarSystem }"
            class="search-dropdown w-100"
            style="min-width: 10rem"
          >
            <template v-slot:no-options>
              Start typing to get systems list...
            </template>
          </v-select>
        </span>
      </p>
      <div class="control">
        <a
          class="button is-primary"
          id="searchBtn"
          title="Find solar system"
          @click.prevent="addWaypoint"
        >
          add
        </a>
      </div>
    </div>
  </div>
  <!-- /WaypointsForm -->
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useWaypointsStore } from '@/stores/waypoints';
import { fetchSolarSystems } from '@/services/api';

interface Emits {
  (e: 'build-route'): void;
}

const emit = defineEmits<Emits>();

const selectedSolarSystem = ref('');
const waypointsStore = useWaypointsStore();
const searchSolarSystemsOptions = ref<string[]>([]);

const searchSolarSystemsList = async (search: string, loading: Function) => {
  searchSolarSystemsOptions.value = await fetchSolarSystems(search, loading);
};

/**
 * Add waypoint
 */
const addWaypoint = (): void => {
  if (selectedSolarSystem.value.length > 1) {
    waypointsStore.add(selectedSolarSystem.value);
    selectedSolarSystem.value = '';
    searchSolarSystemsOptions.value = [];
  }
};

onMounted(() => {
  /**
   * Check if we have preset waypoints in Url query.
   */
  const route = useRoute();
  const presetWaypoints: string[] = route.query.waypoints
    ? (route.query.waypoints as string).split(',')
    : [];
  presetWaypoints.forEach((value) => {
    if (value.length > 1) {
      waypointsStore.add(value);
    }
  });
  emit('build-route');
});
</script>
