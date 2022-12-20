<template>
  <!-- SearchBar -->
  <div class="search-bar is-dark-1 p-4 my-1">
    <p class="title is-3 has-text-white">Solar System</p>
    <p class="subtitle is-6 has-text-white">
      Search for a solar system and track your character location:
    </p>

    <div id="searchBar" class="field has-addons">
      <div class="control">
        <input
          id="search"
          class="input"
          type="text"
          placeholder="Jita"
          autocomplete="off"
          v-model="systemSearch"
        />
        <div
          class="suggestions has-background-white has-text-black py-1 px-3"
          style="display: none"
        ></div>
      </div>
      <div class="control">
        <a
          class="button is-primary"
          id="searchBtn"
          title="Find solar system"
          @click.prevent="$emit('updateSystem', systemSearch)"
        >
          <font-awesome-icon icon="fa-solid fa-search" class="mr-2" /> search</a
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
          <font-awesome-icon
            icon="fa-solid fa-location-crosshairs"
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

<script setup>
import axios from "axios";
import { ref, watch } from "vue";

const props = defineProps({
  systemName: String,
});

const systemSearch = ref("Jita");
const isGettingLocation = ref(false);
const emit = defineEmits(["updateSystem"]);

const updateWithCurrentLocation = () => {
  if (isGettingLocation.value === true) {
    return;
  }

  isGettingLocation.value = true;
  axios.get(`/api/getLocation`).then((response) => {
    if (response.data !== props.systemName) {
      emit("updateSystem", response.data);
    }
    isGettingLocation.value = false;
  });
};

// Auto-refresh
const autoRefresh = ref(false);
let refreshTimer = null;
watch(autoRefresh, (value) => {
  value
    ? (refreshTimer = setInterval(updateWithCurrentLocation, 15000))
    : refreshTimer
    ? clearInterval(refreshTimer)
    : (refreshTimer = null);
});
</script>
