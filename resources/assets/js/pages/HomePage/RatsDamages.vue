<template>
  <div class="is-dark-1 p-5 my-3">
    <div class="content">
      <h3 class="title has-text-white">Rats Damage Types</h3>

      <div v-if="isLoading">
        <progress
          class="progress is-primary is-radiusless"
          style="height: 0.5rem"
        ></progress>
      </div>
      <div class="columns">
        <div class="column is-size-4 has-text-warning">Enemy</div>
        <div class="column is-size-4 has-text-warning">Use Hardeners</div>
        <div class="column is-size-4 has-text-warning">Use Damage</div>
      </div>
      <div
        v-for="(item, index) in ratsDamages"
        :key="item.race"
        class="columns"
      >
        <div class="column py-0">{{ item.race }}</div>
        <div class="column py-0">{{ item.damageToGet }}</div>
        <div class="column py-0">{{ item.damageToUse }}</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { RatsDamage } from '@/structures/rats-damage';

var ratsDamages = ref([] as RatsDamage[]);
const isLoading = ref(true);

onMounted(async () => {
  await axios.get<Array<RatsDamage>>('/api/getRatsDamages').then((response) => {
    ratsDamages.value = response.data;
    isLoading.value = false;
  });
});
</script>
