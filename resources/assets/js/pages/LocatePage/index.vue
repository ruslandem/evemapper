<template>
  <div class="template has-text-white h-100">
    <search-bar
      @update-system="updateSystem"
      :system-name="system?.solarSystemName"
    />
    <div class="locator columns is-desktop">
      <div
        class="column is-two-thirds-desktop"
        :class="{ 'is-hidden': system?.solarSystemName }"
      >
        <div class="is-dark-1 p-4 my-1">
          <div class="has-text-centered is-size-3 m-5 has-text-info">
            No system selected.
          </div>
        </div>
      </div>

      <div
        class="column is-two-thirds-desktop"
        :class="{ 'is-hidden': !system?.solarSystemName }"
        style=""
      >
        <system-info :system="system" />
        <trade-hubs
          v-if="!system?.wormholeClass"
          :jumps="jumps"
          :system-name="system?.solarSystemName"
        />
        <cosmic-signatures :system-name="system?.solarSystemName" />
      </div>
      <div class="column">
        <location-history
          :system-name="system?.solarSystemName"
          @update-system="updateSystem"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { SolarSystem } from '@/structures/solar-system';
import { HubsJump } from '@/structures/hubj-jump';
import { fetchSolarSystemInfo } from '@/services/api';
import SearchBar from './SearchBar.vue';
import SystemInfo from './SystemInfo.vue';
import CosmicSignatures from './CosmicSignatures.vue';
import TradeHubs from './TradeHubs.vue';
import LocationHistory from './LocationHistory.vue';

const system = ref<SolarSystem>();
const jumps = ref<HubsJump>();

const updateSystem = async (name: string): Promise<void> => {
  const solarSystemInfo = await fetchSolarSystemInfo(name);

  if (solarSystemInfo.system) {
    system.value = solarSystemInfo.system;
  }

  if (solarSystemInfo.jumps) {
    jumps.value = solarSystemInfo.jumps;
  }
};
</script>
