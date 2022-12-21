<template>
  <div class="template has-text-white" style="height: 100%">
    <SearchBar
      @update-system="updateSystem"
      :system-name="system.solarSystemName"
    />
    <div class="locator columns is-desktop" >
      <div
        class="column is-two-thirds-desktop"
        :class="{ 'is-hidden': system.solarSystemName }"
      >
        <div class="is-dark-1 p-4 my-1">
          <div class="has-text-centered is-size-3 m-5 has-text-info">
            No system selected.
          </div>
        </div>
      </div>

      <div
        class="column is-two-thirds-desktop"
        :class="{ 'is-hidden': !system.solarSystemName }"
        style=""
      >
        <SystemInfo :system="system" />
        <TradeHubs
          v-if="!system?.wormholeClass"
          :jumps="jumps"
          :systemName="system.solarSystemName"
        />
        <Signatures
          :signatures="signatures"
          :system-name="system.solarSystemName"
          @delete-signature="deleteSignature"
          @update-signatures="updateSystem"
        />
      </div>
      <div class="column">
        <History :locations="locations" @updateSystem="updateSystem" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
/**
 * Imports: funcs
 */
import axios from "axios";
import { ref, onMounted } from "vue";
import { getAxiosPostConfig } from "@/services/utils";
import { toast } from "bulma-toast";
/**
 * Imports: structures
 */
import { SolarSystem } from "@/structures/SolarSystem";
import { HubsJump } from "@/structures/HubsJump";
import { Signature } from "@/structures/Signature";
import { VisitedLocation } from "@/structures/VisitedLocation";
/**
 * Imports: components
 */
import SearchBar from "./SearchBar.vue";
import SystemInfo from "./SystemInfo.vue";
import Signatures from "./Signatures.vue";
import TradeHubs from "./TradeHubs.vue";
import History from "./History.vue";
/**
 * References
 */
const system = ref({} as SolarSystem);
const jumps = ref({} as HubsJump);
const signatures = ref([] as Array<Signature>);
const locations = ref([] as Array<VisitedLocation>);

/**
 * Update system information including signatures and history list.
 * @async
 * @param {string|null} name
 * @returns {Promise<void>}
 */
const updateSystem = async (name: string | null = null): Promise<void> => {
  if (name === null) {
    name = system.value.solarSystemName;
  }
  if (name !== null && name.length > 2) {
    await axios.get(`/api/getSolarSystemInfo/${name}`).then((response) => {
      system.value = response.data.system || {};
      jumps.value = (response.data.jumps as HubsJump) || {};
    });
    await fetchSignatures(name);
    await fetchHistory();
  }
};

/**
 * Fetch signatures for specific solar system and updates `signatures` object.
 * @async
 * @param systemName - Solar system name.
 */
const fetchSignatures = async (systemName: string) => {
  await axios.get(`/api/getSignatures/${systemName}`).then((response) => {
    signatures.value = (response.data.data as Array<Signature>) || [];
  });
};

const fetchHistory = async () => {
  await axios.get(`/api/getLocationsHistory`).then((response) => {
    locations.value = (response.data as Array<VisitedLocation>) || [];
  });
};

const deleteSignature = (id: string, systemName: string): void => {
  axios
    .post(
      "/api/deleteSignature",
      {
        id: id,
        systemName: systemName,
      },
      getAxiosPostConfig()
    )
    .then(() => {
      fetchSignatures(systemName);
      toast({
        message: `Signature ${id} deleted`,
        type: "is-success",
      });
    });
};

onMounted(() => {
  fetchHistory();
});
</script>
