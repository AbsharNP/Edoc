<template>
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar Column (left) -->
        <div class="col-md-3 col-lg-2 bg-light vh-100 p-0">
          <AdminSidebar />
        </div>
  
        <!-- Main Content (right) -->
        <div class="col-md-9 col-lg-10 p-4">
          <h1 class="text-center mb-4">Appointments Overview</h1>
          <div class="chart-container">
            <apexchart
              width="500"
              type="donut" 
              :options="options"
              :series="series"
            ></apexchart>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import VueApexCharts from 'vue3-apexcharts'; // Correct import for Vue 3
  import axios from 'axios';
  import AdminSidebar from '../../../layoutComponents/adminSidebar.vue'; // Adjust the path as necessary
  
  export default {
    name: 'AppointmentsOverview',
    components: {
      apexchart: VueApexCharts, // Register ApexCharts component
      AdminSidebar, // Register the AdminSidebar component
    },
    data() {
      return {
        options: {
          chart: {
            id: 'appointments-chart',
            title: {
              text: 'Appointment Status Overview',
              align: 'center',
              style: {
                fontSize: '20px',
                fontWeight: 'bold',
                color: '#333',
              },
            },
          },
          labels: ['Completed', 'Not Completed'], // Set labels for the donut chart
          colors: ['#28a745', '#dc3545'], 
        },
        series: [], // Initialize series as empty
      };
    },
    mounted() {
      this.fetchAppointmentStats(); // Fetch stats on mount
    },
    methods: {
      fetchAppointmentStats() {
        // Fetch appointment statistics from your API
        axios.post('/analyse')
          .then(response => {
            console.log(response.data); // Log the response
            const { total, completed, not_completed } = response.data;
  
            // Set the series data for the donut chart
            this.series = [completed, not_completed]; // Only completed and not completed
          })
          .catch(error => {
            console.error('Error fetching appointment stats:', error);
          });
      },
    },
  };
  </script>
  
  <style scoped>
  .chart-container {
    height: 400px; /* Ensure the chart has height */
  }
  h1 {
    color: #333; /* Title color */
  }
  </style>
  