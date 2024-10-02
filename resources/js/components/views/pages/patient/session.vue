<template>
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 bg-light vh-100 p-0">
      <patSidebar />
    </div>

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 layout-px-spacing">
      <div class="container">
        <!-- <div v-if="error" class="alert alert-danger">{{ error }}</div> -->
        <div v-if="!sessions.length" class="alert alert-info">No sessions available.</div>
        <table class="table table-striped table-hover" v-if="sessions.length">
          <thead>
            <tr>
              <th>Session ID</th>
              <th>Date</th>
              <th>Start Time</th>
              <th>End Time</th>
              <th>No. of Sessions</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="session in sessions" :key="session.id">
              <td>{{ session.id }}</td>
              <td>{{ session.session_date ? formatDate(session.session_date) : 'N/A' }}</td>
              <td>{{ session.start_time ? formatTime(session.start_time) : 'N/A' }}</td>
              <td>{{ session.end_time ? formatTime(session.end_time) : 'N/A' }}</td>
              <td>{{ session.no_of_sessions }}</td>
              <td>
                <!-- <router-link to="/appointment"> -->
                <button class="btn btn-secondary btn-sm" @click="bookSession(session.id)">Book</button>
              <!-- </router-link> -->
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import PatSidebar from '../../../layoutComponents/patientSidebar.vue';

export default {
  components: {
    PatSidebar,
  },
  setup() {
    const route = useRoute();
    const sessions = ref([]);
    const doctorId = ref(route.query.doctorId);
    const error = ref(null);

    onMounted(async () => {
      await fetchSessions();
    });

    const fetchSessions = async () => {
      try {
        const response = await axios.post(`/sessions/${doctorId.value}`);
        const allSessions = response.data;

        // Get today's date at midnight
        const today = new Date();
        today.setHours(0, 0, 0, 0); // Set time to 00:00:00

        // Filter sessions to only include today or future dates
        sessions.value = allSessions.filter(session => {
          const sessionDate = new Date(session.session_date);
          sessionDate.setHours(0, 0, 0, 0); // Set time to 00:00:00 for comparison
          return sessionDate >= today;
        });

        // Sort sessions by session_date
        sessions.value.sort((a, b) => new Date(a.session_date) - new Date(b.session_date));
      } catch (err) {
        console.error('Error fetching sessions:', err);
        error.value = 'Failed to load sessions. Please try again.';
      }
    };

    const formatDate = (dateString) => {
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    };

    const formatTime = (timeString) => {
      return new Date(`1970-01-01T${timeString}Z`).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    };

    const bookSession = (sessionId) => {
      console.log(`Booking session with ID: ${sessionId}`);
      // Implement booking logic
    };

    return {
      sessions,
      doctorId,
      error,
      formatDate,
      formatTime,
      bookSession,
    };
  },
};
</script>

<style scoped>
.table-hover tbody tr:hover {
  cursor: pointer;
}
</style>
