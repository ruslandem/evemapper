<template>
    <!-- WaypointsForm -->
  <div class="waypoints-form is-dark-1 p-4 my-1">
    <p class="title is-3 has-text-white">Solar System</p>
    <p class="subtitle is-6 has-text-white">
      Search for a solar system and track your character location:
    </p>

    <div id="searchBar" class="field has-addons">
      <p class="control w-100">
        <span class="select w-100">
          <v-select
            v-model="selectedSystem"
            style="min-width:10rem"
            @search="fetchSolarSystems"
            :options="selectOptions"
            class="search-dropdown w-100"
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
          @click.prevent="addWaypoint"
        >
         add</a        >
      </div>
    </div>
  </div>
  <!-- /WaypointsForm -->
</template>

<script setup lang="ts">
import { useWaypointsStore } from '@/stores/waypoints';
import axios from 'axios';
import { ref } from 'vue';

const selectedSystem = ref("");
const selectOptions = ref([]);
const wp = useWaypointsStore();

const fetchSolarSystems = (search: String, loading: Function) => {
  if (search.length > 1) {
    loading(true);
    axios.get(`/api/getSolarSystems/${search}`).then((response) => {
      selectOptions.value = response.data;
      loading(false);
    });
  }
};

const addWaypoint = () => {
    wp.add(selectedSystem.value);
    selectedSystem.value = "";
    selectOptions.value = [];
};

</script>