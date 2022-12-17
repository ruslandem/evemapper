<template>
  <div class="is-dark-half p-5 my-3">
    <div class="content">
      <h3 class="title has-text-white">Rats Damage Types</h3>

      <div class="columns">
        <div class="column is-size-4 has-text-warning">Enemy</div>
        <div class="column is-size-4 has-text-warning">Use Hardeners</div>
        <div class="column is-size-4 has-text-warning">Use Damage</div>
      </div>
      <div v-for="(item, index) in ratsDamages" :key="index" class="columns">
        <div class="column py-0">{{ item.race }}</div>
        <div class="column py-0">{{ item.damageToGet }}</div>
        <div class="column py-0">{{ item.damageToUse }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import axios from "axios";
import { RatsDamage } from "@/structures/RatsDamage";

var ratsDamages = ref([] as RatsDamage[]);

onMounted(async () => {
  await axios.get<Array<RatsDamage>>("/api/getRatsDamages").then((response) => {
    ratsDamages.value = response.data;
  });
});
</script>
