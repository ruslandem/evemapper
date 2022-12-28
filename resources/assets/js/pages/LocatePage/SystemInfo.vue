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
            <td>{{ system?.regionName }}</td>
          </tr>
          <tr>
            <td>Constellation</td>
            <td>{{ system?.constellationName }}</td>
          </tr>
          <tr>
            <td>Rats</td>
            <td>{{ system?.rats }}</td>
          </tr>

          <tr v-if="system?.wormholeClass">
            <td>Wormhole Class</td>
            <td>{{ system?.wormholeClass }}</td>
          </tr>
          <tr v-if="system?.wormholeClass">
            <td>Wormhole Statics</td>
            <td v-if="system?.wormholeStatics">
              <span
                v-for="(value, index) in system?.wormholeStatics"
                :key="index"
                class="mr-2"
              >
                {{ index }}
                <span :style="getWormholeStaticColor(+value.inClass)">
                  ({{ getWormholeStaticType(+value.inClass) }})
                </span>
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
    </div>
  </div>
</template>

<style scoped>
td:nth-child(1) {
  background-color: hsl(0, 0%, 21%);
}
</style>

<script setup lang="ts">
import { PropType, computed } from 'vue';
import {
  getSecurityStatusStyle,
  getWormholeStaticColor,
  getWormholeStaticType
} from '@/services/utils';
import { SolarSystem } from '@/structures/solar-system';
import ButtonLink from '@/components/ui/ButtonLink.vue';

const props = defineProps({
  system: {} as PropType<SolarSystem>
});

const linkDotlan = computed(() => {
  return [
    'https://evemaps.dotlan.net/map/',
    props.system?.regionName?.replace(' ', '_'),
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
</script>