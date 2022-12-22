<template>
  <div class="locator columns is-desktop my-1 px-2">
    <div class="column is-one-third-desktop is-dark-1 p-4 mx-1">
      <WaypointsForm />
      <WaypointsList />

      <div class="my-4 has-text-centered w-100">
        <button class="button is-primary" @click="buildRoute">
          <font-awesome-icon icon="fa-solid fa-route" class="mr-2" />
          get route
        </button>
      </div>
    </div>
    <div class="column is-dark-1 p-4 mx-1">
      <pre>{{ route }}</pre>
    </div>
  </div>
</template>

<script setup lang="ts">
import WaypointsForm from "./WaypointsForm.vue";
import WaypointsList from "./WaypointsList.vue";
import { useWaypointsStore } from "@/stores/waypoints";
import { getAxiosPostConfig } from "@/services/utils";
import { RouteWaypoints } from "@/structures/RouteWaypoints";
import axios from "axios";
import { ref } from "vue";

const wp = useWaypointsStore();
const route = ref({} as RouteWaypoints);

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
      const routeResponse: RouteWaypoints | null = response.data;
      if (routeResponse !== null) {
        route.value = routeResponse;
      }
    });
};
</script>
