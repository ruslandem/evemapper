<template>
  <div
    v-for="(waypoint, index) in wp.waypoints"
    :key="index"
    class="card p-0 mb-1"
  >
    <div class="card-content py-2 px-4">
      {{ waypoint }}
      <span v-if="index == 0" class="has-text-warning is-size-7 mx-2">
        origin
      </span>
      <span
        v-if="index == Object.keys(wp.waypoints).length - 1"
        class="has-text-success is-size-7 mx-2"
      >
        destination
      </span>
      <span class="is-pulled-right">
        <a
          href="#"
          @click.prevent="deleteWaypoint(waypoint)"
          title="Delete waypoint"
        >
          <font-awesome-icon
            icon="fa-solid fa-xmark"
            class="mx-1 has-text-danger"
            mask="fa-solid fa-circle"
            transform="shrink-7"
          />
        </a>
      </span>
      <span class="is-pulled-right">
        <a
          href="#"
          title="Set as origin"
          v-if="index != 0"
          @click.prevent="wp.setOrigin(waypoint)"
        >
          <font-awesome-icon
            icon="fa-solid fa-o"
            class="mx-1 has-text-warning"
            mask="fa-solid fa-circle"
            transform="shrink-7"
          />
        </a>
        <a
          href="#"
          title="Set as destination"
          v-if="index != Object.keys(wp.waypoints).length - 1"
          @click.prevent="wp.setDestination(waypoint)"
        >
          <font-awesome-icon
            icon="fa-solid fa-d"
            class="mx-1 has-text-success"
            mask="fa-solid fa-circle"
            transform="shrink-7"
          />
        </a>
      </span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useWaypointsStore } from "@/stores/waypoints";

const wp = useWaypointsStore();
wp.waypoints = ["Jita", "Amarr", "Hek", "Ohmahailen", "Rens"];

const deleteWaypoint = (waypoint: string): void => {
  wp.remove(waypoint);
};
</script>
