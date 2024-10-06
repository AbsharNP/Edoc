<template>
  <div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar bg-light vh-100 p-0">
      <PatSidebar />
    </div>

    <!-- Main Content -->
    <div class="container mt-4">
      <!-- Header Section -->
      <div class="card p-4 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4">Welcome!</h1>
            <h2 class="h2"><span class="un">{{ userName }}</span></h2>
            <p class="text-muted">
              Haven't any idea about doctors? No problem, let's jump to the 
              <strong>
                "<a href="/doc-list" class="doctor-link">All Doctors</a>"
              </strong>.<br />
              Track your past and future appointments history.
              Also find out the expected arrival time of your doctor or medical consultant.
            </p>
          </div>
          <div class="text-center date-container" style="width: 200px;">
            <p class="mb-0 text-muted">Today's Date</p>
            <h3 class="date-display">{{ todayDate }}</h3>
          </div>
        </div>
      </div>

      <!-- Cards Section -->
      <div class="row mb-4">
        <div class="col-md-6" v-for="card in cards" :key="card.title">
          <!-- Use router-link for internal navigation -->
          <router-link :to="card.link" class="card-link">
            <div class="card text-center p-3 shadow-sm mb-3">
              <h4 class="display-6">{{ card.title }}</h4>
              <p class="text-muted">{{ card.description }}</p>
            </div>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PatSidebar from '../../../layoutComponents/patientSidebar.vue';

export default {
  components: {
    PatSidebar,
  },
  data() {
    return {
      todayDate: new Date().toISOString().split('T')[0],
      userName: localStorage.getItem('userName') ,
      cards: [
        { title: 'My Appointments', description: 'Track all your current appointments', link: '/my-appointment' },
        { title: 'History', description: 'View your appointment history', link: '/appointment-history' },
        { title: 'Prescriptions', description: 'Check your prescribed medications', link: '/prescriptions' },
        { title: 'Medical Reports', description: 'View your uploaded medical reports', link: '/medical-reports' }
      ],
    };
  },
};
</script>

<style scoped>
/* Sidebar Styling */
.sidebar {
  width: 18%;
}

/* General Styling */
.content-container {
  margin-left: 18%;
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

.date-display {
  margin-top: 0.2rem;
  font-size: 1.75rem;
}

.card-link {
  text-decoration: none;
  color: inherit; /* Ensures text color remains unchanged */
}

.un{
  font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
  color: #46a1ec;
}
.doctor-link {
  color: #2196f3; /* Blue color for the link */
  text-decoration: none; /* Remove underline */
  font-weight: bold; /* Keep bold to match the <strong> tag */
  transition: color 0.3s ease; /* Smooth transition for hover effect */
}

.doctor-link:hover {
  color: #1769aa; /* Darker shade on hover */
}

</style>
