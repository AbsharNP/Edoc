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
              <h2 class="h2">Test Patient.</h2>
              <p class="text-muted">
                Haven't any idea about doctors? No problem, let's jump to the 
                <strong>"All Doctors"</strong> section or <strong>"Sessions"</strong>.<br />
                Track your past and future appointments history.
                Also find out the expected arrival time of your doctor or medical consultant.
              </p>
              <div class="search-container d-flex">
                <input v-model="searchQuery" class="form-control" placeholder="Search Department" />
                <button @click="searchByDepartment" class="btn btn-primary ml-2">Search</button>
              </div>
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
            <div class="card text-center p-3 shadow-sm mb-3">
              <h4 class="display-6">{{ card.title }}</h4>
              <p class="text-muted">{{ card.description }}</p>
            </div>
          </div>
        </div>
  
        <!-- Doctor Search Results -->
        <div v-if="doctorResults.length" class="mt-4">
          <h3>Doctors in {{ searchQuery }} Department</h3>
          <div class="row">
            <div class="col-md-3" v-for="doctor in doctorResults" :key="doctor.id">
              <div class="card p-3 shadow-sm">
                <h4>{{ doctor.name }}</h4>
                <p>{{ doctor.specialty }}</p>
                <button class="btn btn-primary">View Profile</button>
              </div>
            </div>
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
        searchQuery: '',
        doctorResults: [], // To store searched doctors
        cards: [
          { title: 'My Appointments', description: 'Track all your current appointments' },
          { title: 'History', description: 'View your appointment history' },
          { title: 'Prescriptions', description: 'Check your prescribed medications' },
          { title: 'Medical Reports', description: 'View your uploaded medical reports' }
        ],
      };
    },
    methods: {
      searchByDepartment() {
        // Replace this mock data with real API call to search doctors by department
        this.doctorResults = [
          { id: 1, name: 'Dr. John Doe', specialty: 'Cardiology' },
          { id: 2, name: 'Dr. Jane Smith', specialty: 'Neurology' },
          { id: 3, name: 'Dr. Alex Johnson', specialty: 'Orthopedics' },
        ];
      },
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
  
  .search-container {
    width: 100%;
    max-width: 400px;
  }
  
  /* Adjusting card layout */
  .card {
    padding: 1rem; /* Less padding for smaller cards */
  }
  
  @media (max-width: 768px) {
    .content-container {
      margin-left: 0;
    }
  
    .sidebar {
      width: 100%;
    }
  }
  </style>
  