<template>
<div class="d-flex">
<div class="sidebar bg-light vh-100 p-0">
      <PatSidebar />
    </div>
  <div class="container">
    
    <div class="appointment-list">
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Phone Number</th>
            <th>Updated Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="appointment in appointments" :key="appointment.id">
            <td>{{ appointment.patient_name }}</td>
            <td>{{ appointment.phone_number }}</td>
            <td>{{ formatDate(appointment.updated_at) }}</td>
            <td>
              <button class="prescription-btn" @click="showPrescription(appointment)">Prescription</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Prescription Popup -->
      <div v-if="showPopup" class="popup-overlay" @click="handleOverlayClick">
        <div class="popup-container" @click.stop>
          <div class="prescription-container">
            <div class="header">
              <h1>PRESCRIPTION</h1>
              <p>Treatment Facility</p>
              <div class="facility-info">
                <div class="left">
                  <p><strong>Address:</strong> {{ facilityAddress }}</p>
                  <p><strong>Phone:</strong> {{ facilityPhone }}</p>
                </div>
                <div class="right">
                  <p><strong>Attended Physician:</strong> Dr. {{ selectedAppointment.dr_name }}</p>
                </div>
              </div>
            </div>
<hr>
            <div class="patient-info">
              <h4>Patient Info:</h4>
              <p><strong>Name:</strong> {{ selectedAppointment.patient_name }}</p>
              <p><strong>Phone:</strong> {{ selectedAppointment.phone_number }}</p>
              <p><strong>Updated Date:</strong> {{ formatDate(selectedAppointment.updated_at) }}</p>
            </div>
<hr>
            <div class="rx-section">
              <h5>Prescription:</h5>
              <p>{{ selectedAppointment.prescription }}</p>
            </div>

            <div class="submit-section">
              <button class="download-btn" @click="downloadPDF">
                <svg xmlns="http://www.w3.org/2000/svg" 
                width="24" height="24" 
                viewBox="0 0 24 24" 
                fill="none" stroke="currentColor" 
                stroke-width="2" stroke-linecap="round" 
                stroke-linejoin="round" 
                class="feather feather-download">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
                </svg>
                Download as PDF
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import axios from 'axios';
import jsPDF from 'jspdf';
import PatSidebar from '../../../layoutComponents/patientSidebar.vue';

export default {
  components: {
    PatSidebar,
  },
  data() {
    return {
      appointments: [], 
      selectedAppointment: null, 
      showPopup: false, 
      facilityAddress: '123 Treatment Ave', 
      facilityPhone: '123-456-7890', 
      userId: localStorage.getItem('userId'), 
    };
  },
  mounted() {
    this.fetchAppointments(); 
  },
  methods: {
    fetchAppointments() {
      axios.post(`/getprescription?user_id=${this.userId}`)
        .then(response => {
          this.appointments = response.data;
        })
        .catch(error => {
          console.error("Error fetching appointments:", error);
        });
    },

    showPrescription(appointment) {
      this.selectedAppointment = appointment; 
      this.showPopup = true; 
    },

    formatDate(dateString) {
      const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
      const date = new Date(dateString);
      return date.toLocaleDateString('en-GB', options);
    },

    handleOverlayClick() {
      this.showPopup = false;  // Close the popup when clicked outside the container
    },

    downloadPDF() {
      const doc = new jsPDF();

      doc.setFontSize(18);
      doc.text('PRESCRIPTION', 20, 20);
      doc.setFontSize(12);
      doc.text(`Treatment Facility: ${this.facilityAddress}`, 20, 30);
      doc.text(`Facility Phone: ${this.facilityPhone}`, 20, 40);
      doc.text(`Attending Physician: Dr. ${this.selectedAppointment.dr_name}`, 20, 50);
      doc.text('----------------------------------------', 20, 60);

      doc.text('Patient Info:', 20, 70);
      doc.text(`Name: ${this.selectedAppointment.patient_name}`, 20, 80);
      doc.text(`Phone: ${this.selectedAppointment.phone_number}`, 20, 90);
      doc.text(`Updated Date: ${this.formatDate(this.selectedAppointment.updated_at)}`, 20, 100);

      doc.text('----------------------------------------', 20, 110);
      doc.setFontSize(14);
      doc.text('Prescription:', 20, 120);
      doc.setFontSize(12);
      doc.text(this.selectedAppointment.prescription, 20, 130);

      doc.save(`Prescription_${this.selectedAppointment.patient_name}.pdf`);
    }
  },
};
</script>

<style scoped>
.container {
  display: flex; /* Use flexbox layout */
  height: 100vh; /* Full height for the container */
}

.sidebar {
  width: 250px; /* Set a fixed width for the sidebar */
}

.appointment-list {
  flex: 1; /* Allow this section to take remaining space */
  padding: 20px; /* Add padding for better spacing */
  overflow-y: auto; /* Optional: enable scrolling if content is too long */
}

/* Table Styles */
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
}

/* Button Styles */
.prescription-btn {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 20px;
  cursor: pointer;
}

.prescription-btn:hover {
  background-color: #63a6ef;
}

.download-btn {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 20px;
  background-color: #007bff; /* Primary color */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.download-btn:hover {
  background-color: #0056b3; /* Darker on hover */
}

/* Popup Styles */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.popup-container {
  background-color: white;
  padding: 20px;
  border-radius: 10px;
  width: 600px;
}

.prescription-container {
  max-width: 100%;
  margin: auto;
  padding: 20px;
  border: 1px solid #dee2e6;
  border-radius: 10px;
  background-color: #fff;
}

.header {
  text-align: center;
  margin-bottom: 20px;
}

.facility-info {
  display: flex;
  justify-content: space-between;
}

.patient-info {
  margin-top: 20px;
}

.rx-section {
  margin-top: 20px;
}

.submit-section {
  margin-top: 20px;
  text-align: right;
}

.btn {
  padding: 10px 20px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.btn:hover {
  background-color: #0056b3;
}
</style>
