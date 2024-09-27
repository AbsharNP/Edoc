<template>
  <div>
    <!-- Navbar with Sign In and Sign Up buttons -->
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent">
      <div class="container-fluid">
        
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <router-link class="btn btn-outline-light ms-2 blurred-button" to="/login">Sign In</router-link>
            </li>
            <li class="nav-item">
              <router-link class="btn btn-outline-light ms-2 blurred-button" to="/signup">Sign Up</router-link>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Main content with background image -->
    <div
      class="main-content text-center"
      :style="{
        backgroundImage: 'url(/images/dr-img.jpg)',
        backgroundSize: 'cover',
        height: '100vh',
        color: 'white',
      }"
    >
      <div class="container d-flex justify-content-center align-items-center" style="height: 100%;">
        <div class="content">
          <h1>Avoid Hassles & Delays.</h1>
          <p>
            How is health today, Sounds like not good!<br />
            Don't worry. Find your doctor online, book as you wish with eDoc.<br />
            We offer you a free doctor channeling service, Make your appointment now.
          </p>
          <router-link to="/login" class="btn btn-outline-light ms-1 blurred-button">Make Appointment</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Index',
};
</script>

<style scoped>
.navbar {
  position: absolute;
  width: 100%;
  z-index: 100;
}

.main-content {
  text-align: center;
  position: relative;
}

.content {
  color: white;
}

/* Blur effect for buttons */
.blurred-button {
  backdrop-filter: blur(10px);
  background: rgba(255, 255, 255, 0.1); /* Slight white transparent background */
  border-radius: 10px;
  padding: 0.5rem 1rem;
  color: white !important;
  transition: background 0.3s ease;
}

.blurred-button:hover {
  background: rgba(255, 255, 255, 0.2); /* Slightly more opaque on hover */
}

/* Style adjustments for Sign In and Sign Up buttons */
.nav-link {
  font-weight: bold;
}

.btn-outline-light {
  color: white;
  border-color: white;
}

.btn-outline-light:hover {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}
</style>
