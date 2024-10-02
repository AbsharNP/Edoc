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
              <button @click="viewAppointments" class="btn btn-primary">
                View My Appointments
              </button>
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
            <div class="card text-center p-4 shadow-sm">
              <h3 class="display-6">{{ item.count }}</h3>
              <p class="text-muted">{{ item.label }}</p>
            </div>
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
                <th scope="col">No. of Sessions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="sessions.length === 0">
                <td colspan="5" class="text-center">
                  <div class="d-flex justify-content-center align-items-center flex-column">
                    <p class="mt-3 text-muted">We couldn't find anything related to your schedule</p>
                  </div>
                </td>
              </tr>
              <tr v-else v-for="(session, index) in sessions" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ session.s_date }}</td>
                <td>{{ session.start_time }}</td>
                <td>{{ session.end_time }}</td>
                <td>{{ session.no_of_sessions }}</td>
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
        { count: 1, label: 'All Doctors' },
        { count: 2, label: 'All Patients' },
        { count: 0, label: 'New Booking' },
        { count: 0, label: 'Today Sessions' },
      ],
      sessions: [],  // To store the sessions fetched from the API
      userName: localStorage.getItem('userName'),
      doctorId: null,  // Initialize doctorId to null
    };
  },
  computed: {
    // Capitalize the first letter of the username
    capitalizedUserName() {
      return this.userName.charAt(0).toUpperCase() + this.userName.slice(1);
    },
  },
  mounted() {
    this.doctorId = localStorage.getItem('doctorId');  // Fetch doctorId from localStorage
    if (this.doctorId) {
      this.fetchUpcomingSessions();  // Fetch sessions only if doctorId is available
    }
  },
  methods: {
    fetchUpcomingSessions() {
      axios
        .get(`/api/upcoming-sessions/${this.doctorId}`)  // Use doctorId in the API call
        .then((response) => {
          this.sessions = response.data;
        })
        .catch((error) => {
          console.error("Error fetching upcoming sessions:", error);
        });
    },
    viewAppointments() {
      // Handle view appointments button click
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
