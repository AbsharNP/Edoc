<template>
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light vh-100 p-0">
            <AdminSidebar />
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 layout-px-spacing">
            <!-- Add Doctor Button -->
            <div class="pd">

            <div class="d-flex justify-content-end mb-3">
                <button @click="addDoctor" class="btn btn-outline-primary add-doctor-button">Add Doctor</button>
            </div>
            </div>

            <!-- Doctors Table -->
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="panel br-6 p-0">
                        <div class="custom-table">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Doctor ID</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Department</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="doctor in doctors" :key="doctor.id">
                                        <td>{{ doctor.name }}</td>
                                        <td>{{ doctor.docid }}</td>
                                        <td>{{ doctor.email }}</td>
                                        <td>{{ doctor.phone_number }}</td>
                                        <td>{{ doctor.department }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" @click="deleteDoctor(doctor.id)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AdminSidebar from '../../../layoutComponents/adminSidebar.vue';

const doctors = ref([]);

onMounted(() => {
    fetchDoctors();
});

const fetchDoctors = () => {
    // Simulated doctor data
    doctors.value = [
        { id: 1, name: 'Dr. John Doe', docid: 'DOC001', email: 'johndoe@example.com', phone_number: '1234567890', department: 'Cardiology' },
        { id: 2, name: 'Dr. Jane Smith', docid: 'DOC002', email: 'janesmith@example.com', phone_number: '0987654321', department: 'Neurology' },
        { id: 3, name: 'Dr. Emily Clark', docid: 'DOC003', email: 'emilyclark@example.com', phone_number: '1112223334', department: 'Pediatrics' },
        { id: 4, name: 'Dr. Michael Brown', docid: 'DOC004', email: 'michaelbrown@example.com', phone_number: '5556667778', department: 'Orthopedics' },
        { id: 5, name: 'Dr. Lisa Ray', docid: 'DOC005', email: 'lisaray@example.com', phone_number: '9998887776', department: 'Dermatology' },
    ];
};

const deleteDoctor = (doctorId) => {
    if (confirm('Are you sure you want to delete this doctor?')) {
        doctors.value = doctors.value.filter(doctor => doctor.id !== doctorId);
        // Optionally, call an API to delete the doctor from the backend
    }
};

const addDoctor = () => {
    // Action to add a doctor
    console.log('Redirect to add doctor form');
};
</script>

<style scoped>
.table-hover tbody tr:hover {
    cursor: pointer;
}

.add-doctor-button:hover {
    background-color: #007bff; /* Bootstrap primary color */
    color: white; /* Change text color to white */
}
.pd{
    padding: 20px 0px 0px 0px ;
}
</style>
