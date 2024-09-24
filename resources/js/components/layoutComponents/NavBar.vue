<template>
  <div>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
      <div class="container">
        <ul class="nav-list">
          <li v-if="userType === 'admin'">Admin Dashboard</li>
          <li v-if="userType === 'patient'">Patient Dashboard</li>
          <li v-if="userType === 'doctor'">Doctor Dashboard</li>
         
        </ul>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarResponsive"
          aria-controls="navbarResponsive"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
</template>



<style scoped>

</style>

<script>
export default {
  name: 'NavBar',
  data() {
    return {
      userType: null, // Initialize userType
    };
  },
  mounted() {
    // Get userType from localStorage when the component is mounted
    this.userType = localStorage.getItem('userType');
  },
};
</script>
<style scoped>
.nav-list {
  list-style-type: none; /* Remove bullet points */
  padding: 0; /* Remove default padding */
  margin: 0; /* Remove default margin */
}

.nav-list li {
  padding: 8px 0; /* Add padding to list items if needed */
}
</style>

