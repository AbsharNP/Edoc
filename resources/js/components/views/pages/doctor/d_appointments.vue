<template>
  <div class="d-flex">
    <div class="sidebar bg-light vh-100 p-0">
      <DocSidebar />
    </div>
    <div class="container">
      <h3>Your Appointments</h3>

      <!-- Buttons for filtering appointments -->
      <div class="mb-3">
        <button 
          class="btn btn-primary rounded-btn mr-2" 
          :class="{ active: showToday }" 
          @click="fetchTodayAppointments"
        >
          Today's Appointments
        </button>
        <button 
          class="btn btn-secondary rounded-btn" 
          :class="{ active: showAll }" 
          @click="fetchAllAppointments"
        >
          All Appointments
        </button>
      </div>

      <table class="table table-striped">
        <thead>
          <tr>
            <th>Patient Name</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Session ID</th>
            <th>Session Date</th>
            <th v-if="showToday">Action</th> <!-- Action column appears if today is selected -->
          </tr>
        </thead>
        <tbody>
          <tr v-if="appointments.length === 0">
            <td colspan="6" class="text-center">No appointments found.</td> 
          </tr>
          <tr v-for="appointment in appointments.slice(0, 5)" :key="appointment.id">
            <td>{{ appointment.patient_name }}</td>
            <td>{{ appointment.address }}</td>
            <td>{{ appointment.phone_number }}</td>
            <td>{{ appointment.session_id }}</td>
            <td>{{ formatDate(appointment.session_date) }}</td> 
            <td v-if="showToday">
              <router-link 
                :to="`/treatment/${appointment.id}`" 
                :class="[
                  'btn',
                  appointment.treatment_status === 1 ? 'btn-outline-secondary' : 'btn-success',
                  'rounded-btn',
                  'd-flex',
                  'align-items-center'
                ]"
              >
                Go
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right ml-2" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M10.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L9.293 7.5H2.5a.5.5 0 0 0 0 1h6.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                </svg>
              </router-link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import DocSidebar from '../../../layoutComponents/docSidebar.vue';

export default {
  components: {
    DocSidebar,
  },
  data() {
    return {
      appointments: [],  // Holds the list of appointments
      doctorId: localStorage.getItem('doctorId'), // Get the doctorId from localStorage
      showToday: true,   // Set default to show today's appointments
      showAll: false,    // Set default to hide all appointments
    };
  },
  mounted() {
    this.fetchTodayAppointments();  // Fetch today's appointments when the component is mounted
  },
  methods: {
    fetchTodayAppointments() {
      this.showToday = true;
      this.showAll = false;

      // Fetch only today's appointments and limit to 5
      axios.post(`/appointments/today/${this.doctorId}`)
        .then(response => {
          this.appointments = response.data.map(appointment => {
            appointment.session_date = this.formatDate(appointment.session_date); // Format session date
            return appointment;
          }).slice(0, 5);  // Limit results to 5
        })
        .catch(error => {
          console.error("Error fetching today's appointments:", error);
        });
    },

    fetchAllAppointments() {
      this.showToday = false;
      this.showAll = true;

      // Fetch all appointments
      axios.post(`/appointments/${this.doctorId}`)
        .then(response => {
          this.appointments = response.data.map(appointment => {
            appointment.session_date = this.formatDate(appointment.session_date); // Format session date
            return appointment;
          });
        })
        .catch(error => {
          console.error("Error fetching all appointments:", error);
        });
    },

    // Format date to 'dd/mm/yyyy'
    formatDate(dateString) {
      const options = { day: '2-digit', month: '2-digit', year: '2-digit' };
      const date = new Date(dateString);
      return date.toLocaleDateString('en-GB', options); // 'en-GB' is for dd/mm/yyyy format
    },
  },
};
</script>

<style scoped>
.d-flex {
  display: flex;
}

.sidebar {
  width: 18%;
  background-color: #f8f9fa;
  overflow-y: auto;
}

.content-container {
  margin-left: 18%;
  padding: 20px;
  max-width: 100%;
}

/* Button Styles */
.btn {
  padding: 8px 16px;
  cursor: pointer;
  font-size: 14px;
}

.btn-primary {
  background-color: #007bff;
  border: none;
  color: white;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-secondary {
  background-color: #6c757d;
  border: none;
  color: white;
}

.btn-secondary:hover {
  background-color: #5a6268;
}

.btn-success {
  background-color: #28a745;
  border: none;
  color: white;
}

.btn-success:hover {
  background-color: #218838;
}

.rounded-btn {
  border-radius: 30px; /* Rounded edges */
}

.active {
  background-color: #28a745 !important;
  color: white;
}

/* Spacing between buttons */
.mr-2 {
  margin-right: 10px;
}

/* Table Styles */
.table {
  margin-top: 1rem;
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #dee2e6;
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f8f9fa;
}

.table tbody tr:hover {
  background-color: #e9ecef;
  cursor: pointer;
}

.d-flex.align-items-center {
  display: flex;
  align-items: center;
}

.ml-2 {
  margin-left: 8px;
}
</style>
