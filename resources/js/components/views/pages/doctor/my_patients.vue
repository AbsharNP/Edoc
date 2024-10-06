<template>
    <div class="d-flex">
      <div class="sidebar bg-light vh-100 p-0">
        <DocSidebar />
      </div>
      <div class="content-container">
        <h3>Treatment List</h3>
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Patient Name</th>
              <th>Phone Number</th>
              <th>Treatment Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="appointments.length === 0">
              <td colspan="3" class="text-center">No treatments found.</td>
            </tr>
            <tr v-for="appointment in appointments" :key="appointment.id">
              <td>
                <!-- Patient Name as a clickable link -->
                <a href="#" @click.prevent="showDetails(appointment)">
                  {{ appointment.patient_name }}
                </a>
              </td>
              <td>{{ appointment.phone_number }}</td>
              <td>{{ appointment.treatment_status }}</td>
            </tr>
          </tbody>
        </table>
  
        <!-- Modal for displaying details -->
        <div class="modal" :class="{ 'is-active': showModal }">
          <div class="modal-background" @click="closeModal"></div>
          <div class="modal-content">
            <div class="box">
              <h4 class="title is-4">Patient Details</h4>
              <p><strong>Name:</strong> {{ selectedAppointment.patient_name }}</p>
              <p><strong>Phone Number:</strong> {{ selectedAppointment.phone_number }}</p>
              <p><strong>Email:</strong> {{ selectedAppointment.email }}</p>
              <p><strong>Prescription:</strong> {{ selectedAppointment.prescription }}</p>
              <button class="button is-danger" @click="closeModal">Close</button>
            </div>
          </div>
        </div>
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
        appointments: [], // Holds the list of appointments
        doctorId: localStorage.getItem('doctorId'), // Assuming you store the doctorId in localStorage
        sessionId: localStorage.getItem('sessionId'), // Assuming sessionId is also stored in localStorage
        showModal: false, // Controls modal visibility
        selectedAppointment: {}, // Holds details of the selected appointment for the popup
      };
    },
    mounted() {
      this.fetchAppointments();
    },
    methods: {
      // Fetch appointments for completed treatments based on doctorId and sessionId
      fetchAppointments() {
        axios.post(`/appointments/treatments/completed/${this.doctorId}/${this.sessionId}`)
          .then(response => {
            this.appointments = response.data;
          })
          .catch(error => {
            console.error("Error fetching treatments:", error);
          });
      },
  
      // Show patient details in the modal
      showDetails(appointment) {
        this.selectedAppointment = appointment;
        this.showModal = true;
      },
  
      // Close the modal
      closeModal() {
        this.showModal = false;
      },
    },
  };
  </script>
  
  <style scoped>
  /* Main Layout Styles */
  .d-flex {
    display: flex;
  }
  
  .sidebar {
    width: 18%;
    background-color: #f8f9fa;
    overflow-y: auto;
  }
  
  .content-container {
    margin-left: 18%;
    padding: 20px;
    max-width: 100%;
  }
  
  /* Table Styles */
  .table {
    margin-top: 1rem;
    width: 100%;
    border-collapse: collapse;
  }
  
  .table th,
  .table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
  }
  
  .table-striped tbody tr:nth-of-type(odd) {
    background-color: #f8f9fa;
  }
  
  .table tbody tr:hover {
    background-color: #e9ecef;
    cursor: pointer;
  }
  
  /* Modal Styles */
  .modal {
    display: none;
  }
  
  .modal.is-active {
    display: flex;
  }
  
  .modal-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(10, 10, 10, 0.86);
    z-index: 40;
  }
  
  .modal-content {
    position: relative;
    margin: auto;
    background-color: white;
    border-radius: 6px;
    max-width: 600px;
    padding: 2rem;
    z-index: 50;
  }
  
  .button.is-danger {
    background-color: #f14668;
    color: #fff;
    border: none;
  }
  
  .button.is-danger:hover {
    background-color: #d23b57;
  }
  </style>
  