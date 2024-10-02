<template>
  <div>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a class="navbar-brand" href="#">Appointment Registration</a>
      </div>
    </nav>

    <!-- Appointment Form -->
    <div class="appointment-container container mt-5">
      <div class="form-card shadow-lg p-4 rounded">
        <!-- Back Button -->
        <div class="d-flex justify-content-start mb-3">
          <router-link to="/doc-list" class="btn btn-outline-secondary btn-sm">
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
        </div>

        <!-- Form Heading -->
        <h2 class="text-center mb-4">Book an Appointment</h2>

        <form @submit.prevent="bookAppointment" class="needs-validation" novalidate>
          <!-- Doctor's Name and Department -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="doctorName" class="form-label">Doctor's Name</label>
              <input
                type="text"
                v-model="doctorName"
                id="doctorName"
                class="form-control"
                readonly
              />
            </div>

            <div class="col-md-6">
              <label for="departmentName" class="form-label">Department</label>
              <input
                type="text"
                v-model="departmentName"
                id="departmentName"
                class="form-control"
                readonly
              />
            </div>
          </div>

          <!-- Available Date -->
          <div class="row mb-3">
            <div class="col-md-6 mb-3">
              <label for="appointmentDate" class="form-label">Available Date</label>
              <input
                type="date"
                v-model="form.appointmentDate"
                id="appointmentDate"
                class="form-control"
                @change="updateSessionDetails"
                required
              />
              <div class="invalid-feedback">Please select an appointment date.</div>
            </div>
          </div>

          <!-- Display Session Details -->
          <div v-if="sessionDetails" class="mb-4">
            <h4>Session Details:</h4>
            <p><strong>Start Time:</strong> {{ sessionDetails.start_time }}</p>
            <p><strong>End Time:</strong> {{ sessionDetails.end_time }}</p>
            <p><strong>Max Sessions Available:</strong> {{ maxSessions }}</p>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="patientName" class="form-label">Patient's Name</label>
              <input
                type="text"
                v-model="form.patientName"
                id="patientName"
                class="form-control"
                placeholder="Enter patient's name"
                required
              />
              <div class="invalid-feedback">Please enter a patient's name.</div>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary btn-block mt-3">Book Appointment</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>



<script>
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
  data() {
    return {
      form: {
        patientName: "",
        appointmentDate: "",
      },
      sessions: [], 
      sessionDetails: null, // Store selected session details here
      doctorName: '',
      departmentName: '',
      maxSessions: 0,
    };
  },
  mounted() {
    this.fetchSessions();
    this.doctorName = this.capitalizeFirstLetter(this.$route.query.doctorName || '');
    this.departmentName = this.capitalizeFirstLetter(this.$route.query.departmentName || '');
  },
  methods: {
    fetchSessions() {
      axios.post('/sessions')
        .then(response => {
          console.log('Sessions fetched:', response.data);
          this.sessions = response.data;
          this.maxSessions = this.sessions.length; // Assuming max sessions is total sessions available
        })
        .catch(error => {
          console.error('Error fetching sessions:', error);
        });
    },

    updateSessionDetails() {
      const selectedDate = this.form.appointmentDate;

      // Ensure the selected date is logged for debugging
      console.log('Selected Date:', selectedDate);

      const selectedSessions = this.sessions.filter(session => session.date === selectedDate);

      // Log the filtered sessions for debugging
      console.log('Filtered Sessions:', selectedSessions);

      if (selectedSessions.length > 0) {
        this.sessionDetails = selectedSessions[0]; // Take the first session details
      } else {
        this.sessionDetails = null; // Reset if no sessions are found for the date
      }
    },

    bookAppointment() {
      if (!this.sessionDetails) {
        Swal.fire({
          icon: 'error',
          title: 'No session available',
          text: 'Please select a date with an available session.',
        });
        return; // Prevent booking if no session details are available
      }
      
      axios.post('/book-appointment', { ...this.form, sessionAvailable: this.sessionDetails.id }) 
        .then(response => {
          Swal.fire({
            icon: 'success',
            title: 'Appointment booked successfully',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            padding: '2em',
          });
          this.resetForm(); // Reset the form after successful booking
          this.$router.push('/patient-dash'); // Redirect to the doctor view page
        })
        .catch(error => {
          console.error('Error booking appointment:', error);
          Swal.fire({
            icon: 'error',
            title: 'Booking failed',
            text: error.response.data.message || 'An error occurred. Please try again.',
          });
        });
    },

    resetForm() {
      this.form.patientName = "";
      this.form.appointmentDate = "";
      this.sessionDetails = null; // Reset session details
    },

    // Method to capitalize the first letter of a string
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }
  },
};
</script>





<style scoped>
/* Navigation Bar */
.navbar {
  margin-bottom: 30px;
}

/* Main Container */
.appointment-container {
  max-width: 800px;
  margin: 0 auto;
  padding-top: 20px;
}

/* Form Card */
.form-card {
  background-color: #f8f9fa;
  border: 1px solid #ddd;
  border-radius: 8px;
  transition: box-shadow 0.3s ease;
}

/* Card Hover Effect */
.form-card:hover {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

/* Form Labels */
.form-label {
  font-weight: bold;
  color: #495057;
}

/* Form Controls */
.form-control,
.form-select {
  border-radius: 6px;
  transition: border-color 0.3s ease;
}

/* Focused Input */
.form-control:focus,
.form-select:focus {
  border-color: #007bff;
  box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Submit Button */
.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
  padding: 10px 20px;
  border-radius: 8px;
  width: 100%;
  transition: background-color 0.3s ease;
}

/* Hover effect for button */
.btn-primary:hover {
  background-color: #0056b3;
  border-color: #0056b3;
}

/* Back Button */
.btn-outline-secondary {
  font-size: 14px;
}

/* Responsive Design */
@media (max-width: 576px) {
  .appointment-container {
    padding: 10px;
  }

  .btn-primary {
    width: auto;
    display: block;
    margin: 0 auto;
  }
}
</style>
