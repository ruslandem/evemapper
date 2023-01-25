<template>
  <div class="is-dark-1 p-4 my-1">
    <div class="has-text-centered is-size-1 mb-2">
      {{ system?.solarSystemName }}
    </div>

    <div class="has-text-centered mb-2">
      <table class="table is-bordered mx-auto">
        <tbody>
          <tr>
            <td>Security</td>
            <td :style="[getSecurityStatusStyle(system?.security)]">
              {{ system?.security?.toFixed(2) }}
            </td>
          </tr>
          <tr>
            <td>Region</td>
            <td>{{ system?.region?.regionName }}</td>
          </tr>
          <tr>
            <td>Constellation</td>
            <td>{{ system?.constellation?.constellationName }}</td>
          </tr>
          <tr>
            <td>Rats</td>
            <td>
              {{ system?.wormhole ? 'Sleepers' : system?.region?.rats?.rats }}
            </td>
          </tr>

          <tr v-if="system?.wormhole">
            <td>Wormhole Class</td>
            <td>{{ system?.wormhole.class }}</td>
          </tr>
          <tr v-if="system?.wormhole?.statics">
            <td>Wormhole Statics</td>
            <td>
              <span
                v-for="(value, index) in system?.wormhole?.statics"
                :key="index"
                class="mr-2"
              >
                {{ value.hole }}
                <sup :style="getWormholeStaticColor(+value.inClass)">
                  {{ getWormholeStaticType(+value.inClass) }}
                </sup>
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Link Buttons -->
      <div class="has-text-centered">
        <button-link :href="linkDotlan" text="DotLan Map" class="is-warning" />
        <button-link
          :href="linkZkillboard"
          text="zKillboard"
          class="is-black"
        />
      </div>
      <!-- /Link Buttons -->

      <!-- Adjacent Systems -->
      <div class="mt-5 has-text-centered">
        <span v-for="adjacent in system?.jumps" class="mx-3">
          <a
            href="#"
            @click.prevent="
              $emit('updateSystem', adjacent.to_solar_system.solarSystemName)
            "
            :style="[getSecurityStatusStyle(adjacent.to_solar_system.security)]"
            :data-tooltip="adjacent.to_solar_system.rats"
          >
            {{ adjacent.to_solar_system.solarSystemName }}
            <sup>{{ adjacent.to_solar_system.security.toFixed(1) }}</sup>
          </a>
        </span>
      </div>
      <!-- /Adjacent Systems -->
    </div>
  </div>
</template>

<style scoped>
td:nth-child(1) {
  background-color: hsl(0, 0%, 21%);
}
</style>

<script setup lang="ts">
import { computed } from 'vue';
import {
  getSecurityStatusStyle,
  getWormholeStaticColor,
  getWormholeStaticType
} from '@/services/utils';
import { SolarSystem } from '@/structures/solar-system';
import ButtonLink from '@/components/ui/ButtonLink.vue';

interface Props {
  system?: SolarSystem;
}

const props = defineProps<Props>();

const linkDotlan = computed(() => {
  return [
    'https://evemaps.dotlan.net/map/',
    props.system?.region?.regionName?.replace(' ', '_'),
    '/',
    props.system?.solarSystemName,
    '#sec'
  ].join('');
});

const linkZkillboard = computed(() => {
  return ['https://zkillboard.com/system/', props.system?.solarSystemName].join(
    ''
  );
});

const wormhole = () => {
  return props.system?.wormhole?.static?.split(',');
};
</script>
