<template>
  <div class="is-dark-half p-5 my-3">
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

<script lang="ts">
import { defineComponent } from "vue";
import axios from "axios";

interface wormholeClass {
  name: string;
  classes: string[];
  highlightColor: string;
}

export default defineComponent({
  name: "wormholeClasses",
  data() {
    return {
      wormholeClasses: [] as wormholeClass[],
    };
  },
  methods: {
    async fetchWormholeClasses() {
      const response = await axios.get<wormholeClass[]>(
        "/api/getWormholeClasses"
      );
      this.wormholeClasses = response.data;
    },
  },
  async mounted() {
    await this.fetchWormholeClasses();
  },
});
</script>
