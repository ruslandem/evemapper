<template>
  <!-- Navbar start -->
  <nav class="navbar">
    <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item logo" href="/"> Eve Mapper </a>
        <a
          role="button"
          class="navbar-burger"
          aria-label="menu"
          aria-expanded="false"
        >
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>
      <div class="navbar-menu">
        <div class="navbar-end">
          <router-link class="navbar-item" to="/">Home</router-link>
          <router-link class="navbar-item" to="/locate">Locator</router-link>
          <router-link class="navbar-item" to="/route">Route</router-link>
        </div>
        <div class="navbar-end">
          <span v-if="auth.isAuthenticated">
            <div
              @click="toggleDropdown()"
              class="navbar-item dropdown is-right"
              :class="{ 'is-active': dropdownActive }"
            >
              <div class="dropdown-trigger">
                <button
                  class="button"
                  aria-haspopup="true"
                  aria-controls="profile-menu"
                >
                  <span
                    ><img
                      class="mt-2 mr-3"
                      :src="`https://image.eveonline.com/Character/${auth.character?.id}_32.png`"
                  /></span>
                  <span>{{ auth.character?.name }}</span>
                  <span class="icon is-small">
                    <font-awesome-icon icon="fa-solid fa-caret-down" />
                  </span>
                </button>
              </div>
              <div class="dropdown-menu" role="menu">
                <div class="dropdown-content">
                  <a href="/logout" class="dropdown-item"> Logout </a>
                </div>
              </div>
            </div>
          </span>
          <span v-else>
            <a class="navbar-item" href="/auth" title="Log in with EVE Online">
              <span class="c-icon c-icon-eve-sso-login"></span>
            </a>
          </span>
        </div>
      </div>
    </div>
  </nav>
  <!-- Navbar end -->
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useAuthStore } from "@/stores/auth";

const auth = useAuthStore();

const dropdownActive = ref(false);
const toggleDropdown = () => {
  dropdownActive.value = !dropdownActive.value;
};
</script>
