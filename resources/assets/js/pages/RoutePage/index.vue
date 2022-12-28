<template>
  <div class="locator columns is-desktop my-1 px-2">
    <div class="column is-one-third-desktop is-dark-1 p-4 mx-1">
      <waypoints-form @build-route="buildRoute" />
      <waypoints-list />

      <div class="my-4 has-text-centered w-100">
        <button class="button mx-2 is-primary" @click="buildRoute" :disabled="wp.waypoints.length < 2">
          <fa-icon icon="fas fa-route" class="mr-2" />
          get route
        </button>
        <button class="button mx-2" @click.prevent="clearWaypoints">clear</button>
      </div>
    </div>
    <div class="column is-dark-1 p-4 mx-1">
      <waypoints-route :route="route" />
    </div>
  </div>
</template>

<script setup lang="ts">
import WaypointsForm from "./WaypointsForm.vue";
import WaypointsList from "./WaypointsList.vue";
import WaypointsRoute from "./WaypointsRoute.vue";
import { useWaypointsStore } from "@/stores/waypoints";
import { getAxiosPostConfig } from "@/services/utils";
import axios from "axios";
import { ref } from "vue";
import { SolarSystem } from "@/structures/solar-system";

const wp = useWaypointsStore();
const route = ref([] as SolarSystem[][]);

const buildRoute = () => {
  axios
    .post(
      "/api/getRoute",
      {
        waypoints: wp.waypoints,
      },
      getAxiosPostConfig()
    )
    .then((response) => {
      if (response.status == 200) {
        // @ts-ignore
        route.value = response.data as SolarSystem[][];
      }
    });
};

const clearWaypoints = () => {
  wp.waypoints = [];
  route.value = [];
};
</script>
