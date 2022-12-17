<template>
  <!-- Navbar start -->
  <nav class="navbar">
    <div class="container">
      <div class="navbar-brand">
        <a class="navbar-item is-size-3 has-text-weight-bold logo" href="/">
          Eve Mapper
        </a>
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
          <span v-if="authenticated()">
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
                      :src="`https://image.eveonline.com/Character/${characterId}_32.png`"
                  /></span>
                  <span>{{ characterName }}</span>
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
            <a class="navbar-item" href="/auth">
              <img
                src="@/../img/eve-sso-login-white-large.png"
                alt="Log in with EVE Online"
              />
            </a>
          </span>
        </div>
      </div>
    </div>
  </nav>
  <!-- Navbar end -->
</template>

<script setup>
import { ref } from "vue";

const dropdownActive = ref(false);
const characterId = ref(null);
const characterName = ref(null);

var meta = document.getElementsByTagName("meta");
const authenticated = () => {
  return meta["character-id"] != null;
};

if (meta["character-id"] != null) {
  characterId.value = meta["character-id"].content;
  characterName.value = meta["character-name"].content;
}

const toggleDropdown = () => {
  dropdownActive.value = !dropdownActive.value;
};
</script>
