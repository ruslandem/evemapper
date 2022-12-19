<template>
  <div class="is-dark-1 p-5 my-3">
    <div class="content">
      <h3 class="title has-text-white">Wormhole Types</h3>

      <div class="columns has-text-centered">
        <div
          v-for="(wormholeClass, index) in wormholeClasses"
          :key="index"
          class="column"
          :style="{ color: wormholeClass.highlightColor }"
        >
          <div class="is-size-4">{{ wormholeClass.name }}</div>
          <div
            v-for="(value, position) in wormholeClass.classes"
            :key="position"
          >
            {{ value }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import axios from "axios";
import { WormholeClass } from "../../structures/WormholeClass";

var wormholeClasses = ref([] as WormholeClass[]);

onMounted(async () => {
  await axios
    .get<Array<WormholeClass>>("/api/getWormholeClasses")
    .then((response) => {
      wormholeClasses.value = response.data;
    });
});
</script>
