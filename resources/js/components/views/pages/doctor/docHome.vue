<template>
  <div>
    <div class="d-flex">
      <!-- Sidebar -->
      <div class="sidebar bg-light vh-100 p-0">
        <DocSidebar />
      </div>

      <!-- Main Content -->
      <div class="container">
        <!-- Header Section -->
        <div class="card p-4 mb-4 shadow-sm">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h1 class="h4">Welcome!</h1>
              <h2 class="h2">Dr. {{ capitalizedUserName }}</h2>
              <p class="text-muted">
                Thanks for joining with us. We are always trying to get you a complete service.
                You can view your daily schedule, Reach Patients Appointment at home!
              </p>
              <router-link to="/appointment">
              <button class="btn btn-primary">
                View My Appointments
              </button>
            </router-link>
            </div>
            <div class="text-center date-container" style="width: 200px;">
              <p class="mb-0 text-muted">Today's Date</p>
              <h3 class="date-display">{{ todayDate }}</h3>
            </div>
          </div>
        </div>

        <!-- Status Section -->
        <div class="row mb-4">
          <div class="col-md-3" v-for="(item, index) in statusItems" :key="index">
            <router-link :to="item.link" class="card-link"> <!-- Add a link around the card -->
              <div class="card text-center p-4 shadow-sm card-hover">
                <h3 class="display-6">{{ item.count }}</h3>
                <p class="text-muted">{{ item.label }}</p>
              </div>
            </router-link>
          </div>
        </div>

        <!-- Upcoming Sessions Section -->
        <div class="card p-4 shadow-sm">
          <h3>Your Upcoming Sessions until Next Week</h3>
          <table class="table table-hover mt-3">
            <thead class="table-light">
              <tr>
                <th scope="col">SI No.</th>
                <th scope="col">Scheduled Date</th>
                <th scope="col">Start Time</th>
                <th scope="col">End Time</th>
                <!-- <th scope="col">No. of Sessions</th> -->
              </tr>
            </thead>
            <tbody>
              <!-- Show this row if no sessions are available -->
              <tr v-if="sessions.length === 0">
                <td colspan="5" class="text-center">
                  <div class="d-flex justify-content-center align-items-center flex-column">
                    <p class="mt-3 text-muted">We couldn't find anything related to your schedule</p>
                  </div>
                </td>
              </tr>

              <!-- Iterate over the sessions fetched from the API -->
              <tr v-else v-for="(session, index) in sessions" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ formatDate(session.session_date) }}</td> <!-- Formatted Date -->
                <td>{{ session.start_time }}</td>
                <td>{{ session.end_time }}</td>
                <!-- <td>{{ session.no_of_sessions }}</td> -->
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DocSidebar from '../../../layoutComponents/docSidebar.vue';
import axios from 'axios';

export default {
  components: {
    DocSidebar,
  },
  data() {
    return {
      todayDate: new Date().toISOString().split('T')[0],
      statusItems: [
        { count: 1, label: 'All Doctors', link: '/doctors' },
        { count: 2, label: 'All Patients', link: '/patients' },
        { count: 0, label: 'New Booking', link: '/bookings' },
        { count: 0, label: 'Today Sessions', link: '/sessions/today' },
      ],
      sessions: [],  // To store the sessions fetched from the API
      userName: localStorage.getItem('userName'),
      doctorId: localStorage.getItem('doctorId'),  // Fetch doctorId from localStorage
    };
  },
  computed: {
    capitalizedUserName() {
      return this.userName ? this.userName.charAt(0).toUpperCase() + this.userName.slice(1) : 'User';
    },
  },
  mounted() {
    if (this.doctorId) {
      this.fetchUpcomingSessions();  // Fetch sessions only if doctorId is available
    } else {
      console.error("No doctorId found in localStorage.");
    }
  },
  methods: {
    fetchUpcomingSessions() {
      axios.post(`/upcsessions/${this.doctorId}`)  // Use 'this.doctorId' to access the data
        .then((response) => {
          this.sessions = response.data;
        })
        .catch((error) => {
          console.error("Error fetching upcoming sessions:", error);
        });
    },
    viewAppointments() {
      router
    },
    formatDate(dateString) {
      const date = new Date(dateString); // Convert string to Date object
      return date.toLocaleDateString('en-GB'); // Format date to dd/mm/yyyy
    },
  },
};
</script>

<style scoped>
/* Sidebar Styling */
.sidebar {
  width: 18%;
  background-color: #f8f9fa;
  overflow-y: auto;
}

.content-container {
  margin-left: 18%;
  max-width: 100%;
}

.vh-100 {
  height: 100vh;
}

.card {
  border-radius: 10px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card-hover:hover {
  transform: scale(1.05); 
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
}

.card-link {
  text-decoration: none;
}

.shadow-sm {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.text-muted {
  color: #6c757d;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
}

.table {
  margin-top: 1rem;
}

.table tbody tr:hover {
  cursor: pointer; /* Change the cursor to a pointer on hover */
  background-color: #f8f9fa; /* Optional: Change background color on hover for better visibility */
}

.date-container {
  text-align: center;
}

.date-display {
  margin-top: 0.2rem;
  font-size: 1.75rem;
}

@media (max-width: 768px) {
  .content-container {
    margin-left: 0;
    max-width: 100%;
  }

  .sidebar {
    width: 100%;
  }

  .date-display {
    font-size: 1.5rem;
  }
}
</style>
