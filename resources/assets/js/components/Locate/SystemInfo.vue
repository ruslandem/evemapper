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
            <td
              :style="[
                system.security >= 0.8
                  ? { color: '#00BFFF' }
                  : system.security >= 0.6
                  ? { color: '#57EDAA' }
                  : system.security >= 0.5
                  ? { color: '#FFD700' }
                  : system.security >= 0
                  ? { color: '#FF8C00' }
                  : { color: '#FF0000' },
              ]"
            >
              {{ system.security?.toFixed(2) }}
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

      <!-- Link Buttons -->
      <div class="has-text-centered mb-4">
        <a
          class="button is-warning"
          target="_blank"
          rel="nofollow noopener noreferrer"
          :href="`https://evemaps.dotlan.net/map/${system.regionName?.replace(
            ' ',
            '_'
          )}/${system.solarSystemName}#sec`"
        >
          DotLan Map
          <i class="fa-solid fa-arrow-up-right-from-square ml-1 fa-xs"></i>
        </a>
        <a
          class="button is-black"
          target="_blank"
          rel="nofollow noopener noreferrer"
          :href="`https://zkillboard.com/system/${system.solarSystemName}`"
        >
          zKillboard
          <i class="fa-solid fa-arrow-up-right-from-square ml-1 fa-xs"></i>
        </a>
      </div>
      <!-- /Link Buttons -->

      <!-- Trade Hubs -->
      <div class="columns">
        <div class="column has-text-centered">
          <h5 class="title">Nearest Trade Hubs</h5>

          <span 
          v-for="(value, index) in jumps"
          :key="index"
          class="tag is-medium is-info m-1"
          >
            {{ index }}: {{ value }}
            <a
              :href="`/route?waypoints=${system.solarSystemName},${index}`"
              class="has-text-warning"
              ><i class="fa-solid fa-route ml-1"></i
            ></a>
          </span>

        </div>
      </div>
      <!-- /Trade Hubs -->
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { SolarSystem } from "@/structures/SolarSystem";
import axios from "axios";

const system = ref({} as SolarSystem);
const jumps = ref([]);

const getInfo = async (solarSystem: String) => {
  await axios.get(`/api/getSolarSystemInfo/${solarSystem}`).then((response) => {
    if (response.data.system) {
      system.value = response.data.system;
    }
    if (response.data.jumps) {
      jumps.value = response.data.jumps;
    }
  });
};

const props = defineProps({
  solarSystem: {
    type: String,
    default: "Jita",
  },
});

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
