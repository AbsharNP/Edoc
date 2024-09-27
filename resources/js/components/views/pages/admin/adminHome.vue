<template>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar Column (left) -->
      <div class="col-md-3 col-lg-2 bg-light vh-100 p-0">
        <AdminSidebar />
      </div>

      <!-- Admin Content (right) -->
      <div class="col-md-9 col-lg-10 p-4">
        <h1 class="text-center mb-4">Admin Home</h1>
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="card text-center">
              <div class="card-body shadow-lg">
                <router-link to="/doctor-view" class="no-underline">
                  <i class="fas fa-user-md card-icon"></i>
                  <h5 class="card-title">Number of Doctors</h5>
                  <p class="card-text">{{ numberOfDoctors }}</p>
                </router-link>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="card text-center">
              <div class="card-body shadow-lg">
                <router-link to="/patient-view" class="no-underline">
                  <i class="fas fa-user-injured card-icon"></i>
                  <h5 class="card-title">Number of Patients</h5>
                  <p class="card-text">{{ numberOfPatients }}</p>
                </router-link>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="card text-center">
              <div class="card-body shadow-lg">
                <router-link to="/appointment-view" class="no-underline">
                  <i class="fas fa-calendar-alt card-icon"></i>
                  <h5 class="card-title">Number of Appointments</h5>
                  <p class="card-text">{{ numberOfAppointments }}</p>
                </router-link>
              </div>
            </div>
          </div>

          <div class="col-md-6 mb-4">
            <div class="card text-center">
              <div class="card-body shadow-lg">
                <router-link to="/department" class="no-underline">
                  <i class="fas fa-building card-icon"></i>
                  <h5 class="card-title">Departments</h5>
                  <p class="card-text">{{ numberOfDepartments }}</p>
                </router-link>
              </div>
            </div>
          </div>
        </div>
        <!-- Add more admin content here -->
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'; // Import Axios for making API requests
import AdminSidebar from '../../../layoutComponents/adminSidebar.vue';

export default {
  name: 'AdminHome',
  components: {
    AdminSidebar,
  },
  data() {
    return {
      numberOfDoctors: 0, // Number of doctors fetched from API
      numberOfPatients: 0, // Replace with actual data from your API
      numberOfAppointments: 0, // Replace with actual data from your API
      numberOfDepartments: 0, // Number of departments fetched from API
    };
  },
  mounted() {
    this.fetchStats(); // Fetch statistics when component is mounted
  },
  methods: {
    fetchStats() {
      // Fetch the data from your API
      axios.post('/doctors')  
        .then(response => {
          this.numberOfDoctors = response.data.length; // Assuming response returns an array of doctors
        })
        .catch(error => {
          console.error('Error fetching doctors:', error);
        });

      axios.post('/departments')  
        .then(response => {
          this.numberOfDepartments = response.data.length; // Assuming response returns an array of departments
        })
        .catch(error => {
          console.error('Error fetching departments:', error);
        });
    },
  },
};
</script>

<style scoped>
/* Add any additional styles for AdminHome if necessary */
.card {
  margin-bottom: 20px;
  transition: transform 0.2s; /* Add transition for card hover effect */
}

.card:hover {
  transform: scale(1.05); /* Scale up the card on hover */
}

.card-icon {
  font-size: 48px; /* Increase icon size */
  margin-bottom: 10px; /* Space between icon and text */
  color: #6c757d; /* Icon color - neutral */
}

.no-underline {
  text-decoration: none; /* Remove underline from links */
}

h1 {
  color: #333; /* Title color */
}

.text-center {
  margin-bottom: 20px; /* Space below the title */
}
</style>
