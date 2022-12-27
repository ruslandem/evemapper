<template>
  <div class="p-5 scrollable h-100" style="border: 1px solid darkslategray">
    <div
      v-for="(waypointsRoute, waypointsIndex) in route"
      :key="waypointsIndex"
    >
      <span class="is-size-5 m-0 p-0">
        {{ waypointsRoute[0].solarSystemName }}
        <a
          href="#"
          @click.prevent="
            setWaypointAutopilot(waypointsRoute[0].solarSystemName)
          "
        >
          <fa-icon
            icon="fas fa-location"
            class="mx-1 has-text-warning"
            mask="fas fa-circle"
            transform="shrink-4"
          />
        </a>
      </span>

      <ol style="list-style-position: inside" class="mb-4">
        <li
          v-for="(waypoint, index) in waypointsRoute.slice(1)"
          :key="index"
          :style="[getSecurityStatusStyle(waypoint.security)]"
        >
          {{ waypoint.solarSystemName }}
          <span class="mx-1">({{ waypoint.security.toFixed(1) }})</span>
        </li>
      </ol>
    </div>
  </div>
</template>

<script setup lang="ts">
import { SolarSystem } from "@/structures/SolarSystem";
import { getSecurityStatusStyle } from "@/services/utils";
import axios from "axios";
import { toast } from "bulma-toast";

const props = defineProps({
  route: Array<Array<SolarSystem>>,
});

const setWaypointAutopilot = (name: string) => {
  axios.post("/api/addAutopilotWaypoint", {
    system: name
  }).then(() => {
    toast({
        message: `Waypoint added ${name}`,
        type: "is-success",
      });
  })
};
</script>
