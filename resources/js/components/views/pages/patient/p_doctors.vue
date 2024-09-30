<template>
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light vh-100 p-0">
            <patSidebar />
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 layout-px-spacing">
            <!-- Search Bar -->
            <div class="row layout-top-spacing justify-content-center">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing d-flex justify-content-center">
                    <div class="input-group mb-3" style="width: 40%;">
                        <input
                            v-model="searchQuery"
                            type="text"
                            class="form-control"
                            placeholder="Search by doctor name or department"
                            aria-label="Search"
                        />
                        <button class="btn btn-primary" type="button" @click="searchDoctors">
                            <i class="fas fa-search"></i> <!-- Font Awesome icon -->
                        </button>
                    </div>
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
                                    <tr
                                        v-for="doctor in filteredDoctors"
                                        :key="doctor.id"
                                    >
                                        <td>{{ doctor.name }}</td>
                                        <td>{{ doctor.doc_id }}</td>
                                        <td>{{ doctor.email }}</td>
                                        <td>{{ doctor.phone_number }}</td>
                                        <td>{{ doctor.department ? doctor.department.name : 'N/A' }}</td>
                                        <td>
                                            <router-link
                                                :to="{
                                                    path: '/appointment',
                                                    query: {
                                                        doctorName: doctor.name,
                                                        departmentName: doctor.department ? doctor.department.name : 'N/A',
                                                        doctorId: doctor.id, // Passing doctor's ID as a query parameter
                                                    },
                                                }"
                                            >
                                                <button class="btn btn-outline-primary btn-sm">Get Appointment</button>
                                            </router-link>
                                        </td>
                                    </tr>
                                    <tr v-if="filteredDoctors.length === 0">
                                        <td colspan="6" class="text-center">No doctors found</td>
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
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import PatSidebar from '../../../layoutComponents/patientSidebar.vue';

export default {
    components: {
        PatSidebar,
    },
    setup() {
        const doctors = ref([]);
        const searchQuery = ref('');
        const error = ref(null);

        // Fetch the list of doctors on component mount
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

        // Computed property to filter doctors based on search query
        const filteredDoctors = computed(() => {
            return doctors.value.filter(doctor => {
                const searchTerm = searchQuery.value.toLowerCase();
                return (
                    doctor.name.toLowerCase().includes(searchTerm) ||
                    (doctor.department && doctor.department.name.toLowerCase().includes(searchTerm))
                );
            });
        });

        return {
            doctors,
            searchQuery,
            filteredDoctors,
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
