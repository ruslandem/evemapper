<template>
  <div class="locator columns is-desktop my-1 px-2">
    <div class="column is-one-third-desktop is-dark-1 p-4 mx-1">
      <appraisal-form @calculate="calculate" />
    </div>
    <div class="column is-dark-1 p-4 mx-1">
      <appraisal-calculations :blueprints="blueprints" />
    </div>
  </div>
</template>

<script setup lang="ts">
import AppraisalForm from './AppraisalForm.vue';
import AppraisalCalculations from './AppraisalCalculations.vue';

import { getBlueprintAppraisal } from '@/services/api';
import { ref } from 'vue';
import { Blueprint } from '@/structures/blueprint';

const blueprints = ref<Blueprint[]>([]);

const calculate = async (data: string) => {
  blueprints.value = [];
  const lines = data.split('\n');
  lines.forEach(async (value) => {
    const items = value.split(/\t/);
    if (items[0].includes('Blueprint')) {
      const response = await getBlueprintAppraisal(items[0]);
      if (response) {
        blueprints.value.push(response);
      }
    }
  });
};
</script>
