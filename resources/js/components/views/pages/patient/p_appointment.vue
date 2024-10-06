<template>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-light vh-100 p-0">
      <PatSidebar />
    </div>

    <!-- Main Content -->
    <div class="container mt-3">
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
            <td>{{ appointment.treatment_completed ? 'Completed' : 'Pending' }}</td>
            <td>
              <button class="btn btn-danger" @click="deleteAppointment(appointment.id)">Delete</button>
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
        this.appointments = response.data;
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
.container {
  max-width: 800px;
  margin: 0 auto;
}

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
