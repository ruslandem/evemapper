<template>
  <table class="table is-bordered is-narrow is-fullwidth">
    <tbody>
      <tr class="has-background-primary">
        <td colspan="7">
          <waypoints-route-title :route="route" />
        </td>
      </tr>
      <tr
        v-for="(waypoint, index) in route.slice(1)"
        :key="index"
        :style="getSecurityStatusStyle(waypoint.security)"
        class="row-route"
      >
        <td>{{ index + 1 }}</td>
        <td>{{ waypoint.solarSystemName }}</td>
        <td>{{ waypoint.security.toFixed(1) }}</td>
        <td>{{ waypoint.regionName }}</td>
        <td>{{ waypoint.rats }}</td>
        <td class="link-cell" @click.prevent="zkbOpen(waypoint.solarSystemID)">
          zkb
        </td>
        <td
          class="link-cell"
          @click.prevent="
            mapOpen(waypoint.solarSystemName, waypoint.regionName)
          "
        >
          map
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
.table.is-narrow td {
  padding: 0.1em 0.5em;
}
.row-route td:nth-child(1) {
  width: 5%;
}
.row-route td:nth-child(3) {
  width: 10%;
}
.row-route td:nth-child(4) {
  width: 20%;
}
.row-route td:nth-child(5) {
  width: 20%;
}
.row-route td:nth-child(6) {
  width: 7%;
}
.row-route td:nth-child(7) {
  width: 7%;
}
.table button {
  height: 1.75em;
}
.link-cell {
  background-color: #282f2f;
  color: white;
  cursor: pointer;
  text-align: center;
}
.link-cell:hover {
  background-color: #353d3d;
}
</style>

<script setup lang="ts">
import { getSecurityStatusStyle } from '@/services/utils';
import { SolarSystem } from '@/structures/solar-system';
import WaypointsRouteTitle from './WaypointsRouteTitle.vue';

interface Props {
  route: SolarSystem[];
}

defineProps<Props>();

const zkbOpen = (solarSystemId: number) => {
  window
    .open(
      `https://zkillboard.com/system/${solarSystemId}/`,
      '_blank',
      'noopener'
    )
    ?.focus();
};

const mapOpen = (solarSystemName: string, regionName: string) => {
  const regionNameParsed = regionName.replace(' ', '_');
  const solarSystemNameParsed = solarSystemName.replace(' ', '_');

  window.open(
    `https://evemaps.dotlan.net/map/${regionNameParsed}/${solarSystemNameParsed}#sec`,
    '_blank',
    'noopener'
  );
};
</script>
