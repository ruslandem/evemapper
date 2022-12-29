<template></template>

<script setup lang="ts">
import { load } from 'recaptcha-v3';

interface Props {
  siteKey: string;
}

interface Emits {
  (e: 'set-token', token:string);
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

if (props.siteKey) {
  load(props.siteKey).then((recaptcha) => {
    recaptcha.execute('contacts').then((token) => {
      emit('set-token', token);
    });
  });
}
</script>
