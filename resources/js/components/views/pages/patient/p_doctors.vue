<template>
    <div class="row">
        <div class="col-md-3 col-lg-2 bg-light vh-100 p-0">
            <patSidebar />
        </div>
        <div class="col-md-9 col-lg-10 layout-px-spacing">
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
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
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
                                    <tr v-for="doctor in filteredDoctors" :key="doctor.id">
                                        <td>{{ doctor.name }}</td>
                                        <td>{{ doctor.id }}</td> <!-- Updated to use doctor.id -->
                                        <td>{{ doctor.email }}</td>
                                        <td>{{ doctor.phone_number }}</td>
                                        <td>{{ doctor.department ? doctor.department.name : 'N/A' }}</td>
                                        <td>
                                            <button class="btn btn-outline-primary btn-sm" @click="getAppointment(doctor.id)">Get Appointment</button> <!-- Updated to use doctor.id -->
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
import { useRouter } from 'vue-router'; // Import useRouter

export default {
    components: {
        PatSidebar,
    },
    setup() {
        const router = useRouter(); // Initialize router
        const doctors = ref([]);
        const searchQuery = ref('');
        const error = ref(null);

        onMounted(() => {
            fetchDoctors();
        });

        const fetchDoctors = async () => {
            try {
                const response = await axios.post('/doctor'); 
                doctors.value = response.data; 
            } catch (error) {
                console.error('Error fetching doctors:', error);
                error.value = 'Failed to load doctors. Please try again.';
            }
        };

        const filteredDoctors = computed(() => {
            return doctors.value.filter(doctor => {
                const searchTerm = searchQuery.value.toLowerCase();
                return (
                    doctor.name.toLowerCase().includes(searchTerm) ||
                    (doctor.department && doctor.department.name.toLowerCase().includes(searchTerm))
                );
            });
        });

        // New method to get appointments
        const getAppointment = async (doctorId) => {
            try {
                const response = await axios.get(`/sessions/${doctorId}`);
                const sessions = response.data;

                // Redirect to the available-sessions route with doctorId and sessions as query parameters
                router.push({ 
                    path: '/available-sessions', 
                    query: { doctorId: doctorId.toString() } 
                });
            } catch (error) {
                console.error('Error fetching sessions:', error);
                error.value = 'Failed to load sessions. Please try again.';
            }
        };

        return {
            doctors,
            searchQuery,
            filteredDoctors,
            error,
            getAppointment, // Return the new method
        };
    },
};
</script>

<style scoped>
.table-hover tbody tr:hover {
    cursor: pointer;
}
.add-doctor-button:hover {
    background-color: #007bff; 
    color: white; 
}
.pd {
    margin-right: 2%;
}
</style>
