<template>
  <div>
    <NavBar />
    

    <div class="main-content">
      <router-view></router-view>
    </div>

    <!-- Footer -->
    <footer>
      <h6 class="text-center">© 2024 Edoc. All rights reserved.</h6>
      <p class="text-center">Committed to providing exceptional healthcare and patient support.</p>
    </footer>
  </div>
</template>

<script>
import NavBar from '../layoutComponents/NavBar.vue';

export default {
  name: 'AppLayout',
  components: {
    NavBar,
  },
};
</script>

<style scoped>
.main-content {
  margin-top: 70px; /* Adjust this value based on your navbar height */
}
</style>
