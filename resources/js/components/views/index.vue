<template>
    <div>
     
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
            <router-link to="/login" class="btn btn-primary">Make Appointment</router-link>
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
    margin-bottom: 0;
  }
  .main-content {
    text-align: center;
  }
  .content {
    color: white;
  }
  </style>