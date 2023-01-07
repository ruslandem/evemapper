<template>
  <!-- Footer start -->
  <footer class="footer">
    <div class="content has-text-centered">
      <p>
        Copyright &copy; <span>ruslandem</span> | All
        <router-link :to="{ name: 'license' }">
          Eve Related Materials
        </router-link>
        are Property Of
        <a
        target="_blank"
        rel="noopener nofollow noreferrer"
        href="http://www.ccpgames.com/"
        >
          CCP Games
        </a>
        |
        <router-link :to="{ name: 'license' }">
          License
        </router-link>
      </p>
    </div>
  </footer>
  <!-- Footer end -->
</template>
