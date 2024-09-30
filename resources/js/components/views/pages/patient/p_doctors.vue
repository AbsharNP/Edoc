<template>
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light vh-100 p-0">
            <patSidebar />
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 layout-px-spacing">
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
                                            <router-link :to="{ 
                                                path: '/appointment', 
                                                query: { 
                                                    doctorName: doctor.name, 
                                                    departmentName: doctor.department ? doctor.department.name : 'N/A' 
                                                } 
                                            }">
                                                <button class="btn btn-outline-primary btn-sm">Get Appointment</button>
                                            </router-link>
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
import PatSidebar from '../../../layoutComponents/patientSidebar.vue';

export default {
    components: {
        PatSidebar,
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

        return {
            doctors,
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
