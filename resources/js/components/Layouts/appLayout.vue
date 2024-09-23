<template>
  <div>
    <!-- Navbar at the top -->
    <NavBar />

    <!-- Main content with padding to prevent overlapping with the fixed navbar -->
    <div class="main-content">
      <router-view></router-view> 
    </div>
    
    <!-- Footer -->
    <footer>App Footer</footer>
  </div>
</template>

<script>
import NavBar from '../layoutComponents/NavBar.vue';
import SidebarComponent from '../layoutComponents/sidebar.vue';


export default {
  name: 'AppLayout',
  components: {
    NavBar,
    SidebarComponent
  },
};
</script>

<style scoped>
.main-content {
  /* Add a top margin equal to the height of the fixed navbar */
  margin-top: 70px; /* Adjust this value based on your navbar height */
}
</style>
