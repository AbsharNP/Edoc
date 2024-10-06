<template>
  <div class="d-flex">
    <div class="sidebar bg-light vh-100 p-0">
      <DocSidebar />
    </div>
    <div class="content-container">
      <h3>Your Appointments</h3>
      <div class="mb-3">
        <div>
          <input 
            type="checkbox" 
            id="today" 
            v-model="showToday" 
            @change="fetchAppointments" 
          />
          <label for="today" class="form-check-label">Today</label>
        </div>
        <div>
          <input 
            type="checkbox" 
            id="all" 
            v-model="showAll" 
            @change="fetchAppointments" 
          />
          <label for="all" class="form-check-label">All</label>
        </div>
      </div>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Patient Name</th>
            <th>Address</th>
            <th>Phone Number</th>
            <th>Session ID</th>
            <!-- <th>Appointment Date</th> -->
            <th>Session Date</th> 
          </tr>
        </thead>
        <tbody>
          <tr v-if="appointments.length === 0">
            <td colspan="6" class="text-center">No appointments found.</td> 
          </tr>
          <tr v-for="appointment in appointments" :key="appointment.id">
            <td>{{ appointment.patient_name }}</td>
            <td>{{ appointment.address }}</td>
            <td>{{ appointment.phone_number }}</td>
            <td>{{ appointment.session_id }}</td>
            <!-- <td>{{ formatDate(appointment.created_at) }}</td> Appointment Date -->
            <td>{{ formatDate(appointment.session_date) }}</td> 
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
      appointments: [],
      doctorId: localStorage.getItem('doctorId'), // Assuming doctorId is stored in localStorage
      showToday: false, // Checkbox for today's appointments
      showAll: true,    // Checkbox for all appointments
    };
  },
  mounted() {
    this.fetchAppointments();
  },
  methods: {
    fetchAppointments() {
      if (this.showToday && !this.showAll) {
        // Fetch only today's appointments
        axios.post(`/appointments/today/${this.doctorId}`)
          .then(response => {
            this.appointments = response.data.map(appointment => {
              appointment.created_at = this.formatDate(appointment.created_at); // Format appointment date
              appointment.session_date = this.formatDate(appointment.session_date); // Format session date
              return appointment;
            });
          })
          .catch(error => {
            console.error("Error fetching today's appointments:", error);
          });
      } else {
        // Fetch all appointments
        axios.post(`/appointments/${this.doctorId}`)
          .then(response => {
            this.appointments = response.data.map(appointment => {
              appointment.created_at = this.formatDate(appointment.created_at); // Format appointment date
              appointment.session_date = this.formatDate(appointment.session_date); // Format session date
              return appointment;
            });
          })
          .catch(error => {
            console.error("Error fetching appointments:", error);
          });
      }
    },
    
    formatDate(dateString) {
      const options = { day: '2-digit', month: '2-digit', year: '2-digit' };
      const date = new Date(dateString);
      return date.toLocaleDateString('en-GB', options); // 'en-GB' is for dd/mm/yy format
    },
  },
};
</script>

<style scoped>
.d-flex {
  display: flex; /* Use flexbox for layout */
}

.sidebar {
  width: 18%; /* Sidebar width */
  background-color: #f8f9fa; /* Light background for sidebar */
  overflow-y: auto; /* Allow scrolling if content overflows */
}

.content-container {
  margin-left: 18%; /* Offset for the content area */
  padding: 20px; /* Add some padding */
  max-width: 100%; /* Ensure full width */
}

/* Table Styles */
.table {
  margin-top: 1rem; /* Spacing above the table */
  width: 100%; /* Ensure the table takes full width */
  border-collapse: collapse; /* Remove double borders */
}

.table th,
.table td {
  padding: 12px; /* Padding for table cells */
  text-align: left; /* Left-align text in cells */
  border-bottom: 1px solid #dee2e6; /* Add a border between rows */
}

.table-striped tbody tr:nth-of-type(odd) {
  background-color: #f8f9fa; /* Background color for odd rows */
}

.table tbody tr:hover {
  background-color: #e9ecef; /* Change background color on hover */
}

.table tbody tr:hover {
  cursor: pointer; /* Change cursor to pointer on hover */
}

.form-check-label {
  margin-left: 5px; /* Margin for better spacing */
}
</style>
