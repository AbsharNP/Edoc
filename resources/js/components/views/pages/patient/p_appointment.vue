<template>
  <div class="container mt-5">
    <h2 class="mb-4">Appointment Booking</h2>
    
    <form @submit.prevent="submitAppointment">
      <div class="mb-3">
        <label for="patientName" class="form-label">Patient Name <span class="text-danger">*</span></label>
        <input type="text" id="patientName" v-model="patientName" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
        <textarea id="address" v-model="address" class="form-control" required></textarea>
      </div>

      <div class="mb-3">
        <label for="phoneNumber" class="form-label">Phone Number <span class="text-danger">*</span></label>
        <input type="tel" id="phoneNumber" v-model="phoneNumber" class="form-control" required />
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" v-model="email" class="form-control" />
      </div>

      <div class="mb-3">
        <label for="sessionId" class="form-label">Session ID</label>
        <input type="text" id="sessionId" v-model="sessionId" class="form-control" readonly />
      </div>

      <button type="submit" class="btn btn-primary">Submit Appointment</button>
    </form>
  </div>
</template>

<script>
import { ref } from 'vue';
import { useRoute } from 'vue-router';

export default {
  setup() {
    const route = useRoute();
    
    // State variables for appointment details
    const patientName = ref('');
    const address = ref('');
    const phoneNumber = ref('');
    const email = ref('');
    const sessionId = ref(route.query.sessionId); // Get sessionId from query parameters

    const submitAppointment = () => {
      // Here you can implement the logic to submit the appointment
      const appointmentData = {
        patientName: patientName.value,
        address: address.value,
        phoneNumber: phoneNumber.value,
        email: email.value,
        sessionId: sessionId.value
      };
      
      console.log('Submitting appointment data:', appointmentData);
      
      // You can use axios to send this data to your backend
      // axios.post('/api/appointments', appointmentData)
      //   .then(response => {
      //     // Handle success response
      //   })
      //   .catch(error => {
      //     // Handle error response
      //   });
    };

    return { patientName, address, phoneNumber, email, sessionId, submitAppointment };
  },
};
</script>

<style scoped>
.text-danger {
  color: red;
}
</style>
