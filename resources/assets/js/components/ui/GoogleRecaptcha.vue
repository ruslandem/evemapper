<template></template>

<script setup lang="ts">
import { load } from 'recaptcha-v3';

const props = defineProps({
  siteKey: {
    type: String
  }
});
const emit = defineEmits(['set-token']);

if (props.siteKey) {
  load(props.siteKey).then((recaptcha) => {
    recaptcha.execute('contacts').then((token) => {
      emit('set-token', token);
    });
  });
}
</script>
