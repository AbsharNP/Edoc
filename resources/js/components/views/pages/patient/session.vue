<template>
  <div class="row">
    <div class="col-md-3 col-lg-2 bg-light vh-100 p-0">
      <patSidebar />
    </div>

    <div class="col-md-9 col-lg-10 layout-px-spacing">
      <div class="container">
        <div v-if="error" class="alert alert-danger">{{ error }}</div>
        <div v-if="!filteredSessions.length && !error" class="alert alert-info">No available sessions.</div>

        <div v-if="doctor" class="doctor-info mb-3">
          <h5>Doctor Information</h5>
          <p><strong>Name:</strong> {{ doctor.name }}</p>
          <p><strong>Email:</strong> {{ doctor.email }}</p>
          <p><strong>Phone Number:</strong> {{ doctor.phone_number }}</p>
        </div>

        <table class="table table-striped table-hover" v-if="filteredSessions.length">
          <thead>
            <tr>
              <th>Date</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>No. of Sessions</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="session in filteredSessions" :key="session.id">
              <td>{{ formatDate(session.session_date) }}</td>
              <td>{{ formatTime(session.start_time) }}</td>
              <td>{{ formatTime(session.end_time) }}</td>
              <td>{{ session.no_of_sessions }}</td>
              <td>
                <button class="btn btn-secondary btn-sm" @click="openModal(session.id)">Book</button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Modal for booking session -->
        <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel">Book a Session</h5>
                <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form @submit.prevent="submitBooking">
                  <div class="mb-3">
                    <label for="patientName" class="form-label">Patient Name <span class="text-danger">*</span></label>
                    <input type="text" id="patientName" v-model="bookingData.patientName" class="form-control" required />
                  </div>

                  <div class="mb-3">
                    <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                    <textarea id="address" v-model="bookingData.address" class="form-control" required></textarea>
                  </div>

                  <div class="mb-3">
                    <label for="phoneNumber" class="form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="tel" id="phoneNumber" v-model="bookingData.phoneNumber" class="form-control" required />
                  </div>

                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" v-model="bookingData.email" class="form-control" />
                  </div>

                  <div class="form-check mb-3">
                    <input type="checkbox" id="termsCheckbox" v-model="bookingData.agreeToTerms" class="form-check-input" required />
                    <label for="termsCheckbox" class="form-check-label">I agree to the terms and conditions <span class="text-danger">*</span></label>
                  </div>

                  <input type="hidden" v-model="bookingData.sessionId" /> <!-- Keep hidden session ID -->
                  
                  <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Submit Appointment</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import axios from 'axios';
import Swal from 'sweetalert2';
import PatSidebar from '../../../layoutComponents/patientSidebar.vue';

export default {
  components: { PatSidebar },

  setup() {
    const route = useRoute();
    const router = useRouter();
    const sessions = ref([]);
    const error = ref(null);
    const doctor = ref(null);
    const doctorId = ref(route.query.doctorId);
    const bookingData = ref({
      patientName: '',
      address: '',
      phoneNumber: '',
      email: '',
      sessionId: '',
      agreeToTerms: false
    });

    onMounted(() => {
      fetchSessions();
    });

    const fetchSessions = async () => {
      try {
        const response = await axios.post(`/sessions/${doctorId.value}`);
        const { doctor: doctorData, sessions: allSessions } = response.data;
        doctor.value = doctorData;
        sessions.value = allSessions;
      } catch (err) {
        console.error('Error fetching sessions:', err.response?.data || err.message);
        error.value = 'Failed to load sessions. Please try again.';
      }
    };

    const filteredSessions = computed(() => {
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      return sessions.value
        .filter(session => {
          const sessionDate = new Date(session.session_date);
          const sessionEndTime = new Date(`${session.session_date}T${session.end_time}`);
          return sessionDate >= today && sessionEndTime >= new Date();
        })
        .sort((a, b) => {
          const dateA = new Date(a.session_date);
          const dateB = new Date(b.session_date);
          
          if (dateA.getTime() !== dateB.getTime()) {
            return dateA - dateB;
          }

          const startTimeA = new Date(`${a.session_date}T${a.start_time}`);
          const startTimeB = new Date(`${b.session_date}T${b.start_time}`);
          return startTimeA - startTimeB;
        });
    });

    const formatDate = (date) => new Date(date).toLocaleDateString(undefined, {
      year: 'numeric', month: 'long', day: 'numeric'
    });

    const formatTime = (time) => {
      if (!time) return 'N/A';
      const [hours, minutes] = time.split(':');
      const date = new Date();
      date.setHours(parseInt(hours), parseInt(minutes));
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
    };

    const openModal = (sessionId) => {
      bookingData.value.sessionId = sessionId; // Set the session ID to booking data
      console.log("Selected session ID:", bookingData.value.sessionId); // Log the session ID
      bookingData.value.agreeToTerms = false; // Reset the checkbox state
      const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
      modal.show();
    };

    const closeModal = () => {
      // Reset booking data
      bookingData.value = {
        patientName: '',
        address: '',
        phoneNumber: '',
        email: '',
        sessionId: '', // Reset the sessionId
        agreeToTerms: false // Reset the checkbox state
      };
      const modal = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
      modal.hide();
    };

    const submitBooking = async () => {
      // Simple check condition before submitting
      if (!bookingData.value.patientName || !bookingData.value.address || !bookingData.value.phoneNumber || !bookingData.value.agreeToTerms) {
        error.value = 'Please fill out all required fields and agree to the terms and conditions.';
        return;
      }

      try {
        await bookAppointment(); // Call the function to book the appointment
        closeModal();

        // Show success alert
        Swal.fire({
          title: 'Appointment Submitted!',
          text: 'Your appointment has been booked successfully.',
          icon: 'success',
          confirmButtonText: 'Okay',
          backdrop: true,
          customClass: {
            popup: 'centered-popup' // Class to adjust position
          },
        });
      } catch (err) {
        console.error('Error booking session:', err.response?.data || err.message);
        error.value = 'Failed to book session. Please try again.';
      }
    };

    const bookAppointment = async () => {
      // API call to save appointment to the database
      const response = await axios.post('/appointments', {
        patient_name: bookingData.value.patientName,
        address: bookingData.value.address,
        phone_number: bookingData.value.phoneNumber,
        email: bookingData.value.email,
        session_id: bookingData.value.sessionId, // Include session ID here
        treatment_completed: 0, // Default to 0 (not completed)
        prescription: null // Assuming no prescription is provided
      });
      return response.data;
    };

    return {
      sessions,
      error,
      doctor,
      filteredSessions,
      formatDate,
      formatTime,
      openModal,
      closeModal,
      submitBooking,
      bookingData
    };
  }
};
</script>

<style scoped>
.centered-popup {
  position: fixed;
  top: 50% !important;
  left: 50% !important;
  transform: translate(-50%, -50%) !important; /* Center the popup */
}
</style>
