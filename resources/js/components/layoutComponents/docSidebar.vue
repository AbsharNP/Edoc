<template>
    <div class="container">
      <!-- Bootstrap Toggle Button -->
      <button
        class="navbar-toggler"
        type="button"
        @click="toggleSidebar"
        aria-controls="navbarResponsive"
        aria-expanded="isSidebarOpen"
        aria-label="Toggle navigation"
        v-if="!isSidebarOpen"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <div class="menu" :class="{ 'menu-closed': !isSidebarOpen }">
        <table class="menu-container" border="0">
          <tr>
            <td style="padding:10px" colspan="2">
              <table border="0" class="profile-container">
                <tr>
                  <td width="30%" style="padding-left:20px">
                    <!-- Profile Image (Uncomment if needed) -->
                    <!-- <img src="@/assets/user.png" alt="user" width="100%" style="border-radius:50%"> -->
                  </td>
                  <td style="padding:0px;margin:0px;">
                    <p class="profile-title">Dr.{{ capitalizedUserName }}</p>
                    <p class="profile-subtitle">{{ userEmail }}</p>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <input
                      type="button"
                      value="Log out"
                      class="logout-btn btn-primary-soft btn"
                      @click="logout"
                    />
                  </td>
                </tr>
              </table>
            </td>
          </tr>
  
          <!-- Sidebar Links -->
          <tr class="menu-row">
            <td class="menu-btn menu-icon-dashbord" :class="{ 'menu-active': isActive('/doctor-dash') }">
              <router-link to="/doctor-dash" class="non-style-link-menu" active-class="non-style-link-menu-active">
                <div class="menu-item">
                  <i class="fas fa-tachometer-alt"></i>
                  <p class="menu-text">Dashboard</p>
                </div>
              </router-link>
            </td>
          </tr>
  
          <!-- Doctors Link -->
          <!-- <tr class="menu-row">
            <td class="menu-btn menu-icon-doctor" :class="{ 'menu-active': isActive('/doctor-view') }">
              <router-link to="/doctor-view" class="non-style-link-menu" active-class="non-style-link-menu-active">
                <div class="menu-item">
                  <i class="fas fa-user-md"></i>
                  <p class="menu-text">Doctors</p>
                </div>
              </router-link>
            </td>
          </tr> -->
  
          <!-- Schedule Link -->
          <tr class="menu-row">
            <td class="menu-btn menu-icon-schedule" :class="{ 'menu-active': isActive('/schedule') }">
              <router-link to="/schedule-sess" class="non-style-link-menu" active-class="non-style-link-menu-active">
                <div class="menu-item">
                  <i class="fas fa-calendar-alt"></i>
                  <p class="menu-text">Schedule</p>
                </div>
              </router-link>
            </td>
          </tr>
  
          <!-- Appointment Link -->
          <tr class="menu-row">
            <td class="menu-btn menu-icon-appointment" :class="{ 'menu-active': isActive('/appointment') }">
              <router-link to="/appointment" class="non-style-link-menu" active-class="non-style-link-menu-active">
                <div class="menu-item">
                  <i class="fas fa-calendar-check"></i>
                  <p class="menu-text">Appointment</p>
                </div>
              </router-link>
            </td>
          </tr>
  
          <!-- Patients Link -->
          <tr class="menu-row">
            <td class="menu-btn menu-icon-patient" :class="{ 'menu-active': isActive('/treatment-completed') }">
              <router-link to="/treatment-completed" class="non-style-link-menu" active-class="non-style-link-menu-active">
                <div class="menu-item">
                  <i class="fas fa-procedures"></i>
                  <p class="menu-text">Patients</p>
                </div>
              </router-link>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'DocSidebar',
    data() {
      return {
        isSidebarOpen: true, // Initial state of the sidebar
        userName: localStorage.getItem('userName') || 'Administrator',
        userEmail: localStorage.getItem('userEmail') || 'admin@edoc.com',
      };
    },
    computed: {
      // Capitalize the first letter of the username
      capitalizedUserName() {
        return this.userName.charAt(0).toUpperCase() + this.userName.slice(1);
      },
    },
    methods: {
      toggleSidebar() {
        this.isSidebarOpen = !this.isSidebarOpen; // Toggle the sidebar state
      },
      isActive(route) {
        return this.$route.path === route; // Check if the current route is active
      },
      async logout() {
        try {
          await axios.post('/logout');
  
          // Clear local storage or session data if required
          localStorage.removeItem('authToken');
          localStorage.removeItem('userName');
          localStorage.removeItem('userEmail');
          localStorage.removeItem('isAuthenticated');
          localStorage.removeItem('userId');
          localStorage.removeItem('userType');
  
  
          // Redirect the user to the login page
          this.$router.push({ name: 'login' });
  
          // Optionally, show a success message (if using a toast system)
          this.$toast.success('Logged out successfully');
        } catch (error) {
          console.error('Error logging out:', error);
          this.$toast.error('Error logging out');
        }
      },
    },
  };
  </script>
  
  <style scoped>
  @import '/public/css/admin.css';
  
  /* Sidebar Styles */
  .menu {
    width: 250px;
    transition: width 0.3s, transform 0.3s;
  }
  
  .menu-closed {
    transform: translateX(-100%);
  }
  
  /* Button Style */
  .navbar-toggler {
    display: block;
    margin: 10px;
    background-color: #007bff;
    border: none;
    padding: 10px;
    color: white;
  }
  
  .menu-row {
    padding: 10px;
  }
  
  .menu-item {
    display: flex;
    align-items: center;
    height: 45px;
  }
  
  .menu-item i {
    margin-right: 8px;
    font-size: 1.2em;
  }
  
  .menu-text {
    margin: 0;
  }
  
  /* Highlight Active Menu */
  .menu-active {
    background-color: #e7f7ff;
  }
  
  /* Responsive Styles */
  @media (max-width: 768px) {
    .menu {
      width: 250px;
    }
  
    .menu-closed {
      transform: translateX(-100%);
    }
  
    .menu-text {
      display: inline;
    }
  
    .menu-spacer {
      display: none;
    }
  }
  </style>
  