<template>
  <!-- SearchBar -->
  <div class="search-bar is-dark-1 p-4 my-1">
    <p class="title is-3 has-text-white">Solar System</p>
    <p class="subtitle is-6 has-text-white">
      Search for a solar system and track your character location:
    </p>

    <div id="searchBar" class="field has-addons">
      <p class="control">
        <span class="select">
          <v-select
            v-model="selectedSystem"
            style="min-width: 20rem"
            @search="searchSolarSystemsList"
            :options="searchSolarSystemsOptions"
            class="search-dropdown"
            ><template v-slot:no-options
              >Start typing to get systems list...</template
            ></v-select
          >
        </span>
      </p>
      <div class="control">
        <a
          class="button is-primary"
          id="searchBtn"
          title="Find solar system"
          @click.prevent="searchSystem"
        >
          <fa-icon icon="fas fa-search" class="mr-2" /> search</a
        >
      </div>
      <div class="control">
        <a
          id="locate"
          href="#"
          class="button is-warning"
          title="Get current location"
          @click.prevent="updateWithCurrentLocation"
        >
          <fa-icon
            icon="fas fa-location-crosshairs"
            :class="{ 'fa-spin': isLoading }"
          />
        </a>
      </div>
      <div class="control ml-5 my-2">
        <input
          id="autoRefreshSwitch"
          type="checkbox"
          class="switch"
          :checked="autoRefresh"
          @click="autoRefresh = !autoRefresh"
        />
        <label for="autoRefreshSwitch">Auto-refresh</label>
      </div>
    </div>

    <div v-if="isLoading">
      <progress
        class="progress is-primary is-radiusless"
        style="height: 0.5rem"
      ></progress>
    </div>
  </div>

  <!-- /SearchBar -->
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { fetchSolarSystems } from '@/services/api';
import { fetchCurrentSystem } from '@/services/api';

interface Props {
  systemName?: string
}

interface Emits {
  (e: 'update-system', solarSystem: string): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const selectedSystem = ref('');
const isLoading = ref(false);

/**
 * Get current character location and updates current solar system accordingly.
 * @async
 * @returns Promise<void>
 */
const updateWithCurrentLocation = async (): Promise<void> => {
  if (isLoading.value == false) {
    isLoading.value = true;
    selectedSystem.value = await fetchCurrentSystem();
    searchSystem();
    isLoading.value = false;
  }
};

/**
 * Auto-refresh
 */
const autoRefresh = ref(false);
let refreshTimer: NodeJS.Timer | null = null;
watch(autoRefresh, (value) => {
  value
    ? (refreshTimer = setInterval(updateWithCurrentLocation, 15000))
    : refreshTimer
    ? clearInterval(refreshTimer)
    : (refreshTimer = null);
});

/**
 * Solar systems search selectbox
 */
const searchSolarSystemsOptions = ref(['']);
const searchSolarSystemsList = async (
  search: string,
  loading: Function
): Promise<void> => {
  searchSolarSystemsOptions.value = await fetchSolarSystems(search, loading);
};

/**
 * Search for selected solar system by emiting `update-system` event.
 * @returns void
 */
const searchSystem = (): void => {
  emit('update-system', selectedSystem.value);
  selectedSystem.value = '';
  searchSolarSystemsOptions.value = [];
};
</script>
