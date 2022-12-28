<template>
  <a
    class="button is-small mx-1"
    :class="props.class"
    target="_blank"
    rel="nofollow noopener noreferrer"
    :href="props.href"
  >
    {{ props.text }}
    <fa-icon icon="fas fa-arrow-up-right-from-square" class="ml-1 fa-xs" />
  </a>
</template>

<script setup>
const props = defineProps(['href', 'text', 'class']);
</script>
