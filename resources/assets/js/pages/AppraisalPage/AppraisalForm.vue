<template>
  <div class="is-dark-1 p-4 my-1">
    <p class="title is-3 has-text-white">Blueprints Appraisal</p>
    <p class="subtitle is-6 has-text-white">
      Get estimated profitability of manufacture using blueprints:
    </p>
    <textarea
      v-model="blueprints"
      class="textarea"
      placeholder="Blueprints"
      rows="10"
    ></textarea>

    <div class="my-4 has-text-centered">
      <button @click.prevent="submitForm" class="button mx-2 is-primary">
        <fa-icon icon="fas fa-hand-holding-dollar" class="mr-2" />
        appraisal
      </button>
      <button @click.prevent="clearForm" class="button mx-2">clear</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const blueprints = ref('');

interface Emits {
  (e: 'calculate', data: string): void;
}
const emit = defineEmits<Emits>();

const clearForm = () => {
  blueprints.value = '';
};

const submitForm = () => {
  emit('calculate', blueprints.value);
};
</script>
