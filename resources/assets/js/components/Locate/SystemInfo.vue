<template>
  <div>
    <div class="has-text-centered is-size-1 mb-4">
      {{ system.solarSystemName }}
    </div>

    <div class="has-text-centered mb-4">
      <table class="table mx-auto">
        <tbody>
          <tr>
            <td>Security</td>
            <td>
              <span class="security">{{ system.security }}</span>
            </td>
          </tr>
          <tr>
            <td>Region</td>
            <td>{{ system.regionName }}</td>
          </tr>
          <tr>
            <td>Constellation</td>
            <td>{{ system.constellationName }}</td>
          </tr>
          <tr>
            <td>Rats</td>
            <td>{{ system.rats }}</td>
          </tr>

          <tr v-if="system.wormholeClass">
            <td>Wormhole Class</td>
            <td></td>
          </tr>
          <tr v-if="system.wormholeClass">
            <td>Wormhole Statics</td>
            <td v-if="system.wormholeStatics">
              <span
                v-for="(value, index) in system.wormholeStatics"
                :key="index"
                style="margin-right: 0.25rem"
              >
                (<span class="class-type" data-in-class="">{{ value }}</span
                >)
              </span>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="has-text-centered mb-4">
        <a
          class="button is-warning"
          target="_blank"
          rel="nofollow noopener noreferrer"
          href="`https://evemaps.dotlan.net/map/{{system.regionName}}/${system.solarSystemName}#sec`"
        >
          DotLan Map
          <i class="fa-solid fa-arrow-up-right-from-square ml-1 fa-xs"></i>
        </a>
        <a
          class="button is-black"
          target="_blank"
          rel="nofollow noopener noreferrer"
          href="https://zkillboard.com/system/{{ urlencode($system->solarSystemID) }}"
        >
          zKillboard
          <i class="fa-solid fa-arrow-up-right-from-square ml-1 fa-xs"></i>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { SolarSystem } from "@/structures/SolarSystem";
import axios from "axios";

const system = ref({} as SolarSystem);

const props = defineProps({
  solarSystem: {
    type: String,
    default: "Jita",
  },
});

const getInfo = async (solarSystem: string) => {
  await axios.get(`/api/getSolarSystemInfo/${solarSystem}`).then((response) => {
    if (response.data.system) {
      system.value = response.data.system;
    }
  });
};

watch(
  () => props.solarSystem,
  () => {
    if (props.solarSystem) {
      getInfo(props.solarSystem);
    }
  }
);

onMounted(() => {
  getInfo(props.solarSystem || "Jita");
});
</script>
