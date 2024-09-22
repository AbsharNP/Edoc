<template>
    <div>
      <!-- <header>Auth Header</header> -->
      <main>
        <router-view></router-view> <!-- This will render your auth-related views -->
      </main>
      <!-- <footer>Auth Footer</footer> -->
    </div>
  </template>
  
  <script>

  export default {
    name: 'AuthLayout',
  };
  </script>
  