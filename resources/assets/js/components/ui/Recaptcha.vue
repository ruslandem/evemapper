<template>
  <button @click="recaptcha">Execute recaptcha</button>
</template>

<script setup>
import { load } from 'recaptcha-v3';

// const { executeRecaptcha, recaptchaLoaded } = useReCaptcha();
// const recaptcha = async () => {
//   await recaptchaLoaded();
//   const token = await executeRecaptcha("contacts");
//   console.log(token);
// };
</script>
