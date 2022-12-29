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
          :systemName="system?.solarSystemName"
        />
        <cosmic-signatures
          :signatures="signatures"
          :system-name="system?.solarSystemName"
          @delete-signature="deleteSignature"
          @update-signatures="updateSystem"
        />
      </div>
      <div class="column">
        <location-history :locations="locations" @updateSystem="updateSystem" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { getAxiosPostConfig } from '@/services/utils';
import { toast } from 'bulma-toast';
import { SolarSystem } from '@/structures/solar-system';
import { HubsJump } from '@/structures/hubj-jump';
import { Signature } from '@/structures/signature';
import { VisitedLocation } from '@/structures/visited-location';
import SearchBar from './SearchBar.vue';
import SystemInfo from './SystemInfo.vue';
import CosmicSignatures from './CosmicSignatures.vue';
import TradeHubs from './TradeHubs.vue';
import LocationHistory from './LocationHistory.vue';
import {
  fetchSignatures,
  fetchHistory,
  fetchSolarSystemInfo
} from '@/services/api';

const system = ref<SolarSystem>();
const jumps = ref<HubsJump>();
const signatures = ref<Signature[]>([]);
const locations = ref<VisitedLocation[]>([]);

/**
 * Update system information including signatures and history list.
 * @async
 * @param {string|null} name
 * @returns {Promise<void>}
 */
const updateSystem = async (name: string | null = null): Promise<void> => {
  if (name === null) {
    name = system.value?.solarSystemName || null;
  }
  if (name && name.length > 1) {
    clearAll();

    const solarSystemInfo = await fetchSolarSystemInfo(name);
    system.value = solarSystemInfo.system;
    jumps.value = solarSystemInfo.jumps;

    signatures.value = await fetchSignatures(name);
    locations.value = await fetchHistory();
  }
};

const clearAll = () => {
  system.value = {} as SolarSystem;
  jumps.value = {} as HubsJump;
  signatures.value = [] as Signature[];
  locations.value = [] as VisitedLocation[];
};

const deleteSignature = (id: string, systemName: string): void => {
  axios
    .post(
      '/api/deleteSignature',
      {
        id: id,
        systemName: systemName
      },
      getAxiosPostConfig()
    )
    .then(async () => {
      signatures.value = await fetchSignatures(systemName);
      toast({
        message: `Signature ${id} deleted`,
        type: 'is-success'
      });
    });
};

onMounted(async () => {
  locations.value = await fetchHistory();
});
</script>
