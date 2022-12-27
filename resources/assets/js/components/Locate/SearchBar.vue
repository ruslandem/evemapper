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
            @search="fetchSolarSystems"
            :options="selectOptions"
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
            :class="{ 'fa-spin': isGettingLocation }"
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
  </div>
  <!-- /SearchBar -->
</template>

<script setup lang="ts">
import axios from "axios";
import { ref, watch } from "vue";

const props = defineProps({
  systemName: String,
});

const selectedSystem = ref("");
const isGettingLocation = ref(false);
const emit = defineEmits(["update-system"]);

const updateWithCurrentLocation = () => {
  if (isGettingLocation.value === true) {
    return;
  }

  isGettingLocation.value = true;
  axios.get(`/api/getLocation`).then((response) => {
    if (response.data !== props.systemName) {
      emit("update-system", response.data);
    }
    isGettingLocation.value = false;
  });
};

// Auto-refresh
const autoRefresh = ref(false);
let refreshTimer: NodeJS.Timer | null = null;
watch(autoRefresh, (value) => {
  value
    ? (refreshTimer = setInterval(updateWithCurrentLocation, 15000))
    : refreshTimer
    ? clearInterval(refreshTimer)
    : (refreshTimer = null);
});

// Solar systems search
const selectOptions = ref([]);
const fetchSolarSystems = (search: String, loading: Function) => {
  if (search.length > 1) {
    loading(true);
    axios.get(`/api/getSolarSystems/${search}`).then((response) => {
      selectOptions.value = response.data;
      loading(false);
    });
  }
};
const searchSystem = () => {
  emit("update-system", selectedSystem.value);
  selectedSystem.value = "";
  selectOptions.value = [];
};
</script>
