<template>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-light vh-100 p-0">
      <PatSidebar />
    </div>

    <!-- Main Content -->
    <div class="container ">
      <h2 class="my-4">My Appointments</h2>

      <div v-if="error" class="alert alert-danger">{{ error }}</div>

      <table class="table table-striped w-100">
        <thead>
          <tr>
            <th>Patient Name</th>
            <th>Address</th>
            <th>Ph no:</th>
            <th>Email</th>
            <th>Date</th>
            <th>Dr. Name</th>
            <th>Status</th>
            <th>Action</th> <!-- Added Action column -->
          </tr>
        </thead>
          <tbody>
            <tr v-for="appointment in appointments" :key="appointment.id">
              <td>{{ capitalizeWords(appointment.patient_name) }}</td>
              <td>{{ appointment.address }}</td>
              <td>{{ appointment.phone_number }}</td>
              <td>{{ appointment.email || 'null' }}</td> <!-- Show 'null' if email is missing -->
              <td>{{ formatDate(appointment.appsession.session_date) }}</td>
              <td>{{ capitalizeWords(appointment.appsession.doctor.name) }}</td>
              <td>{{ appointment.treatment_status ? 'Completed' : 'Pending' }}</td>
              <td>
                <router-link 
                  v-if="appointment.treatment_status === 1" 
                  :to="`/view-prescriptions`" 
                  class="btn btn-outline-info"
                >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                viewBox="0 0 24 24" fill="none" stroke="currentColor" 
                stroke-width="2" stroke-linecap="round" 
                stroke-linejoin="round" class="feather feather-eye">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z">
                </path><circle cx="12" cy="12" r="3"></circle>
                </svg>
                </router-link>
                <button 
                  v-else 
                  class="btn btn-outline-danger" 
                  @click="deleteAppointment(appointment.id)"
                >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" 
                viewBox="0 0 24 24" fill="none" 
                stroke="currentColor" stroke-width="2" 
                stroke-linecap="round" stroke-linejoin="round" 
                class="feather feather-trash">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 
                2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                </path>
                </svg>
                </button>
              </td>
            </tr>
          </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import PatSidebar from '../../../layoutComponents/patientSidebar.vue';

export default {
  components: {
    PatSidebar,
  },
  data() {
    return {
      appointments: [],
      error: null,
    };
  },
  mounted() {
    this.fetchAppointments();
  },
  methods: {
    async fetchAppointments() {
    try {
      const response = await axios.post('/my-appointments');
      this.appointments = response.data.sort((a, b) => {
        const dateA = new Date(a.appsession.session_date);
        const dateB = new Date(b.appsession.session_date);
        return dateA - dateB; // Sort in ascending order (oldest first)
      });
    } catch (error) {
      console.error('Error fetching appointments:', error.response?.data || error);
      this.error = 'Failed to load appointments.';
    }
  },
    async deleteAppointment(id) {
      const result = await Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
      });

      if (result.isConfirmed) {
        try {
          await axios.delete(`/api/appointments/${id}`);
          this.appointments = this.appointments.filter(appointment => appointment.id !== id);
          Swal.fire('Deleted!', 'Your appointment has been deleted.', 'success');
        } catch (error) {
          console.error('Error deleting appointment:', error.response?.data || error);
          this.error = 'Failed to delete appointment.';
          Swal.fire('Error!', 'There was a problem deleting the appointment.', 'error');
        }
      }
    },
    formatDate(dateString) {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
    capitalizeWords(str) {
      return str.split(' ').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()
      ).join(' ');
    },
  },
};
</script>

<style scoped>


.table {
  margin-top: 20px;
  width: 100%;
}

.table th,
.table td {
  text-align: center;
}

.alert {
  margin-top: 20px;
}

.sidebar {
  width: 18%;
}

.vh-100 {
  height: 100vh;
}

.btn-danger {
  margin-left: 10px;
}
</style>
