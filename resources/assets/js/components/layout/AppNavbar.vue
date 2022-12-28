<template>
  <!-- Navbar start -->
  <nav class="navbar">
    <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item logo" to="/">Eve Mapper</a>
        <a
          role="button"
          class="navbar-burger"
          :class="{ 'is-active': navbarBurgerActive }"
          aria-label="menu"
          aria-expanded="false"
          @click="navbarBurgerActive = !navbarBurgerActive"
        >
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
          <span aria-hidden="true"></span>
        </a>
      </div>
      <div class="navbar-menu" :class="{ 'is-active': navbarBurgerActive }">
        <div class="navbar-end">
          <router-link
            @click.prevent="navbarBurgerActive = false"
            class="navbar-item"
            to="/"
            >Home</router-link
          >
          <router-link
            @click.prevent="navbarBurgerActive = false"
            class="navbar-item"
            :to="{ name: 'locate' }"
            >Locator</router-link
          >
          <router-link
            @click.prevent="navbarBurgerActive = false"
            class="navbar-item"
            :to="{ name: 'route' }"
            >Route</router-link
          >
        </div>
        <div class="navbar-end">
          <span v-if="authStore.isAuthenticated">
            <div
              v-if="!navbarBurgerActive"
              @click="toggleDropdown()"
              :class="{ 'is-active': dropdownActive }"
              class="navbar-item dropdown is-right"
            >
              <div class="dropdown-trigger">
                <button
                  class="button"
                  aria-haspopup="true"
                  aria-controls="profile-menu"
                >
                  <span
                    ><img class="mt-2 mr-3" :src="getAvatarImageLink"
                  /></span>
                  <span>{{ authStore.character?.name }}</span>
                  <span class="icon is-small">
                    <fa-icon icon="fas fa-caret-down" />
                  </span>
                </button>
              </div>
              <div class="dropdown-menu" role="menu">
                <div class="dropdown-content">
                  <a href="/logout" class="dropdown-item"> Logout </a>
                </div>
              </div>
            </div>
            <div v-if="navbarBurgerActive">
              <a href="/logout" class="navbar-item"> Logout </a>
            </div>
          </span>

          <span v-if="!authStore.isAuthenticated">
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
import { ref, computed } from 'vue';
import { useAuthStore } from '@/stores/auth';

const authStore = useAuthStore();

/**
 * Dropdown menu
 */
const dropdownActive = ref(false);
const toggleDropdown = (): void => {
  dropdownActive.value = !dropdownActive.value;
};

/**
 * Navbar burger
 */
const navbarBurgerActive = ref(false);

/**
 * Character avatar
 */
const getAvatarImageLink = computed(() => {
  return [
    'https://image.eveonline.com/Character/',
    authStore.character?.id,
    '_32.png'
  ].join('');
});
</script>

<style>
.navbar-menu.is-active .navbar-item {
  border-top: 1px solid white;
  text-align: center;
}
</style>
