<template>
  <div class="template has-text-white" style="height: 100%">
    <SearchBar
      @updateSystem="updateSystem"
      :systemName="system.solarSystemName"
    />
    <div class="locator columns">
      <div
        class="column is-two-thirds"
        :class="{ 'is-invisible': !system.solarSystemName }"
      >
        <SystemInfo :system="system" />
        <TradeHubs :jumps="jumps" :systemName="system.solarSystemName" />
        <Signatures
          :signatures="signatures"
          @delete-signature="deleteSignature"
        />
      </div>
      <div class="column">
        <History :locations="locations" @updateSystem="updateSystem" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import axios from "axios";
import { getAxiosPostConfig } from "@/services/utils";
import { toast } from "bulma-toast";
// Types
import { SolarSystem } from "@/structures/SolarSystem";
import { HubsJump } from "@/structures/HubsJump";
import { Signature } from "@/structures/Signature";
import { VisitedLocation } from "@/structures/VisitedLocation";
// Components
import SearchBar from "./SearchBar.vue";
import SystemInfo from "./SystemInfo.vue";
import Signatures from "./Signatures.vue";
import TradeHubs from "./TradeHubs.vue";
import History from "./History.vue";

const system = ref({} as SolarSystem);
const jumps = ref({} as HubsJump);
const signatures = ref([] as Array<Signature>);
const locations = ref([] as Array<VisitedLocation>);

const updateSystem = async (name: string) => {
  if (name.length > 2) {
    await axios.get(`/api/getSolarSystemInfo/${name}`).then((response) => {
      system.value = response.data.system || {};
      jumps.value = (response.data.jumps as HubsJump) || {};
    });
    await fetchSignatures(name);
    await fetchHistory();
  }
};

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
