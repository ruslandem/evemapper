<template>
  <div class="h-100">
    <h1 class="title is-1 has-text-centered mt-3 mb-5">Contact us</h1>
    <div v-if="!formSent" class="p-6 is-dark-1">
      <div class="content">
        <div>
          <div id="contact_id"></div>
          <div class="field">
            <div class="control has-icons-left">
              <input
                v-model="formData.name"
                type="text"
                name="name"
                id="name"
                class="input"
                placeholder="Name"
                autofocus
              />
              <span class="icon is-left">
                <i class="fa fa-user"></i>
              </span>
            </div>
          </div>
          <div class="field">
            <div class="control has-icons-left">
              <input
                v-model="formData.email"
                type="email"
                name="email"
                id="email"
                class="input"
                placeholder="Email"
              />
              <span class="icon is-left">
                <i class="fa fa-envelope"></i>
              </span>
            </div>
          </div>
          <div class="field">
            <textarea
              v-model="formData.message"
              name="message"
              id="message"
              rows="5"
              class="textarea"
              placeholder="Message"
            ></textarea>
          </div>
          <button
            type="button"
            class="button is-success is-size-5"
            @click.prevent="sendForm"
            :disabled="formData.message?.length < 1"
          >
            Submit
          </button>
        </div>

        <Recaptcha
          site-key="6Ldm-EMjAAAAAB2O9CLGDONd1pa07TXjglX2hEfc"
          @set-token="setToken"
        />
      </div>
    </div>
    <div v-else>
      <div class="p-6 is-dark-1 has-text-centered">
        <div class="content">
          <h3 class="title has-text-success">Thank you!</h3>
          <p>Your message has been sent.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Recaptcha from "@/components/ui/Recaptcha.vue";
import { getAxiosPostConfig } from "@/services/utils";
import { ContactFormData } from "@/structures/ContactFormData";
import axios from "axios";
import { toast } from "bulma-toast";
import { ref } from "vue";
import { event } from "vue-gtag";

const formSent = ref(false);
const recaptchaToken = ref("");
const setToken = (token: string) => {
  recaptchaToken.value = token;
};

const formData = ref({
  name: "",
  email: "",
  message: "",
} as ContactFormData);

const sendForm = () => {
  formData.value["g-recaptcha-response"] = recaptchaToken.value;
  axios
    .post("/api/sendContactForm", formData.value, getAxiosPostConfig())
    .then((response) => {
      if (response.status !== 200) {
        toast({
          message: `Error sending form (${response.statusText})`,
          type: "is-danger",
        });
      } else {
        formSent.value = true;
        event('contactFormSent');
      }
    });
};
</script>
