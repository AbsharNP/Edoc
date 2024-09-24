<template>
  <div>
    <NavBar />
    

    <div class="main-content">
      <router-view></router-view>
    </div>

    <!-- Footer -->
    <footer>App Footer</footer>
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
