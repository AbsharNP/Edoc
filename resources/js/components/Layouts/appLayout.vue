<template>
    <div>
      <!-- <header>App Header</header> -->
      <NavBar />
      <main>
        <router-view></router-view> <!-- This will render your main application views -->
      </main>
      <footer>App Footer</footer>
    </div>
  </template>
  
  <script>
import NavBar from '../layoutComponents/NavBar.vue';

  export default {
    name: 'AppLayout',
    components: {
      NavBar,
    }
  };
  </script>
  