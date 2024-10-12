<template>
  <div class="d-flex">
    <!-- Sidebar Component -->
    <div class="sidebar bg-light vh-100 p-0">
        <DocSidebar />
      </div>
    <!-- Main Content -->
    <div class="main-content flex-grow-1">
      <!-- Navigation Bar -->
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
          <a class="navbar-brand" href="#">Session Management</a>
        </div>
      </nav>

      <!-- Session Form -->
      <div class="session-form-container container mt-5">
        <div class="form-card shadow-lg p-4 rounded">
          <!-- Back Button -->
          <!-- <div class="d-flex justify-content-start mb-3">
            <router-link to="/doctor-dash" class="btn btn-outline-secondary btn-sm">
              <svg xmlns="http://www.w3.org/2000/svg"
                   width="20"
                   height="20"
                   viewBox="0 0 24 24"
                   fill="none"
                   stroke="currentColor"
                   stroke-width="2"
                   stroke-linecap="round"
                   stroke-linejoin="round"
                   class="feather feather-arrow-left-circle me-2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 8 8 12 12 16"></polyline>
                <line x1="16" y1="12" x2="8" y2="12"></line>
              </svg>
              Back
            </router-link>
          </div> -->

          <!-- Form Heading -->
          <h2 class="text-center mb-4">Create Session</h2>

          <form @submit.prevent="createSession" class="needs-validation" novalidate>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="session_date" class="form-label">Session Date</label>
                <input
                  type="date"
                  v-model="form.session_date"
                  id="session_date"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Please select a session date.</div>
              </div>

              <div class="col-md-6 mb-3">
                <label for="start_time" class="form-label">Start Time</label>
                <input
                  type="time"
                  v-model="form.start_time"
                  id="start_time"
                  class="form-control"
                  required
                />
                <div class="invalid-feedback">Please enter a valid start time.</div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="end_time" class="form-label">End Time</label>
                <input
                  type="time"
                  v-model="form.end_time"
                  id="end_time"
                  class="form-control"
                  required
                  @change="calculateDuration"
                />
                <div class="invalid-feedback">Please enter a valid end time.</div>
              </div>

              <div class="col-md-6 mb-3">
                <p class="mt-4">Duration: <strong>{{ duration }} hours</strong></p>
                <!-- <p>Number of Sessions: <strong>{{ numberOfSessions }}</strong></p> -->
              </div>
            </div>

            <div class="text-center">
              <button type="submit" class="btn btn-primary btn-block mt-3">Create Session</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import moment from 'moment'; // Import Moment.js
import DocSidebar from '../../../layoutComponents/docSidebar.vue'; // Sidebar component

export default {
  components: {
    DocSidebar, // Register sidebar component
  },
  data() {
    return {
      form: {
        session_date: '',
        start_time: '',
        end_time: '',
        doctor_id: '', // Added field for doctor_id
        no_of_sessions: '', 
      },
      duration: 0,  // Duration in hours
      numberOfSessions: 0, // New property to store the calculated number of sessions
    };
  },
  mounted() {
    // Retrieve the doctor_id from localStorage
    const doctorId = localStorage.getItem('doctorId');
    if (doctorId) {
      this.form.doctor_id = doctorId;
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Doctor ID not found. Please log in again.',
      });
      this.$router.push('/login'); // Redirect to login if doctor ID is not found
    }
  },
  methods: {
    calculateDuration() {
      if (!this.form.start_time || !this.form.end_time) {
        this.duration = 0;
        this.numberOfSessions = 0;
        this.form.no_of_sessions = 0; // Reset the number of sessions in the form
        return;
      }

      const start = moment(this.form.start_time, 'HH:mm');
      const end = moment(this.form.end_time, 'HH:mm');

      if (!start.isValid() || !end.isValid()) {
        this.duration = 0;
        this.numberOfSessions = 0;
        this.form.no_of_sessions = 0; // Reset the number of sessions in the form
        return;
      }

      let diffInHours = end.diff(start, 'hours');

      if (diffInHours < 0) {
        end.add(1, 'day');
        diffInHours = end.diff(start, 'hours');
      }

      this.duration = diffInHours;
      this.numberOfSessions = this.duration * 3; // Assume each session is 20 minutes (3 sessions per hour)
      this.form.no_of_sessions = this.numberOfSessions; // Store the calculated number of sessions in the form
    },
    createSession() {
      axios.post('/create-sessions', this.form)
        .then(response => {
          Swal.fire({
            icon: 'success',
            title: 'Session created successfully',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            padding: '2em',
          });
          this.resetForm();
          this.$router.push('/doctor-dash');
        })
        .catch(error => {
          Swal.fire({
            icon: 'error',
            title: 'Creation failed',
            text: error.response.data.message || 'An error occurred. Please try again.',
          });
        });
    },
    resetForm() {
      this.form.session_date = '';
      this.form.start_time = '';
      this.form.end_time = '';
      this.form.no_of_sessions = 0;
      this.duration = 0;
      this.numberOfSessions = 0;
    },
  },
};
</script>

<style scoped>
/* Define a layout for the sidebar and main content */
.d-flex {
  display: flex;
}

.sidebar {
  width: 250px;
}

.main-content {
  flex-grow: 1;
}

/* (Your other styles remain unchanged) */
</style>
