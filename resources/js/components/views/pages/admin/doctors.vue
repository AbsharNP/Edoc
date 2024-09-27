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
                    <router-link to="/add-doctor">
                        <button class="btn btn-outline-primary add-doctor-button">
                            <svg xmlns="http://www.w3.org/2000/svg" 
                                 width="24" height="24" viewBox="0 0 24 24" 
                                 fill="none" stroke="currentColor" stroke-width="2" 
                                 stroke-linecap="round" stroke-linejoin="round" 
                                 class="feather feather-plus">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Add Doctor
                        </button>
                    </router-link>
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
                                        <td>{{ doctor.doc_id }}</td>
                                        <td>{{ doctor.email }}</td>
                                        <td>{{ doctor.phone_number }}</td>
                                        <td>{{ doctor.department ? doctor.department.name : 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-sm" @click="confirmDeleteDoctor(doctor.id)">Delete</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-if="error" class="alert alert-danger">{{ error }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Swal from 'sweetalert2'; // Import SweetAlert2
import AdminSidebar from '../../../layoutComponents/adminSidebar.vue';

export default {
    components: {
        AdminSidebar,
    },
    setup() {
        const doctors = ref([]);
        const error = ref(null);

        onMounted(() => {
            fetchDoctors();
        });

        const fetchDoctors = async () => {
            try {
                const response = await axios.post('/doctor'); // Ensure the correct endpoint is used
                doctors.value = response.data; 
            } catch (error) {
                console.error('Error fetching doctors:', error);
                error.value = 'Failed to load doctors. Please try again.';
            }
        };

        const confirmDeleteDoctor = (doctorId) => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteDoctor(doctorId);
                }
            });
        };

        const deleteDoctor = async (doctorId) => {
            try {
                await axios.delete(`/doc/${doctorId}`);
                doctors.value = doctors.value.filter(doctor => doctor.id !== doctorId);
                Swal.fire(
                    'Deleted!',
                    'Doctor has been deleted.',
                    'success'
                );
            } catch (error) {
                console.error('Error deleting doctor:', error);
                Swal.fire(
                    'Error!',
                    'Failed to delete doctor. Please try again.',
                    'error'
                );
            }
        };

        return {
            doctors,
            confirmDeleteDoctor,
            error,
        };
    },
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

.pd {
    margin-right: 2%;
}
</style>
