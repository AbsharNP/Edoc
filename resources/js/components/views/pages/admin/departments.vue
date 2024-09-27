<template>
  <div class="row">
    <div class="col-md-3 col-lg-2 bg-light vh-100 p-0">
      <AdminSidebar />
    </div>

    <div class="col-md-9 col-lg-10 container mt-5">
      <h2 class="text-center mb-4">Departments</h2>

      <!-- Add Department Button -->
      <button class="btn btn-outline-primary mb-3" @click="showAddDepartmentModal">
                <svg xmlns="http://www.w3.org/2000/svg" 
                  width="24" height="24" viewBox="0 0 24 24" 
                  fill="none" stroke="currentColor" stroke-width="2" 
                  stroke-linecap="round" stroke-linejoin="round" 
                  class="feather feather-plus">
                  <line x1="12" y1="5" x2="12" y2="19"></line>
                  <line x1="5" y1="12" x2="19" y2="12"></line>
               </svg>
         Add Department
      </button>

      <!-- Departments Table -->
      <div class="table-container">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th style="width: 40px;">ID</th> <!-- Set width for ID column -->
              <th style="width: 200px;">Name</th> <!-- Set width for Name column -->
            </tr>
          </thead>
          <tbody>
            <tr v-for="(department, index) in departments" :key="department.id">
              <td>{{ index + 1 }}</td>
              <td class="d-flex justify-content-between align-items-center">
                <span>{{ department.name }}</span>
                <button class="btn btn-outline-danger btn-sm" @click="confirmDeleteDepartment(department.id)">
                  <i class="fas fa-trash"></i> <!-- FontAwesome trash icon -->
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Modal for Adding Department -->
      <div class="modal fade" :class="{ show: isModalVisible }"
           :style="{ display: isModalVisible ? 'block' : 'none' }"
           tabindex="-1" role="dialog"
           @click.self="closeAddDepartmentModal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header bg-primary text-white">
              <h5 class="modal-title">Add Department</h5>
            </div>
            <div class="modal-body">
              <form @submit.prevent="addDepartment">
                <div class="form-group">
                  <label for="departmentName">Department Name</label>
                  <input
                    type="text"
                    id="departmentName"
                    v-model="depName"
                    class="form-control"
                    required
                  />
                </div>
                <div class="text-center mt-3">
                  <button type="submit" class="btn btn-outline-primary">Add</button>
                  <span class="pd"></span>
                  <button type="button" class="btn btn-outline-danger" @click="closeAddDepartmentModal">Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Backdrop for Modal -->
      <div v-if="isModalVisible" class="modal-backdrop fade show"></div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import Swal from 'sweetalert2';
import AdminSidebar from '../../../layoutComponents/adminSidebar.vue';

export default {
  name: 'Departments',
  components: {
    AdminSidebar,
  },
  data() {
    return {
      departments: [],
      isModalVisible: false,
      depName: '',
    };
  },
  methods: {
    showAddDepartmentModal() {
      this.isModalVisible = true;
    },
    closeAddDepartmentModal() {
      this.isModalVisible = false;
      this.depName = '';
    },
    fetchDepartments() {
      axios.post('/departments')  
        .then((response) => {
          this.departments = response.data;
        })
        .catch((error) => {
          console.error('Error fetching departments:', error);
        });
    },
    addDepartment() {
      if (this.depName) {
        axios.post('/add-department', {
          name: this.depName,
        })
        .then((response) => {
          this.departments.push(response.data);

          Swal.fire({
            icon: 'success',
            title: 'Department added successfully',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            padding: '2em',
          });

          this.closeAddDepartmentModal();
        })
        .catch((error) => {
          console.error('Error adding department:', error);
        });
      }
    },
    confirmDeleteDepartment(departmentId) {
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
          this.deleteDepartment(departmentId);
        }
      });
    },
    deleteDepartment(departmentId) {
      axios.delete(`/depdlt/${departmentId}`)
        .then((response) => {
          this.departments = this.departments.filter(department => department.id !== departmentId);
          Swal.fire(
            'Deleted!',
            'Department has been deleted.',
            'success'
          );
        })
        .catch((error) => {
          console.error('Error deleting department:', error);
          Swal.fire(
            'Error!',
            'Failed to delete department. Please try again.',
            'error'
          );
        });
    },
  },
  mounted() {
    this.fetchDepartments();
  },
};
</script>

<style scoped>
.modal {
  display: none;
}

.modal.show {
  display: block;
}

.modal-header .btn {
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  background-color: rgba(255, 255, 255, 0.2);
  transition: background-color 0.3s;
}

.modal-header .btn:hover {
  background-color: rgba(255, 255, 255, 0.5);
}

.pd {
  padding: 2px;
}

.icon-large {
  font-size: 2rem;
}

.table-container {
  width: auto; /* Adjust as needed */
  overflow-x: auto; /* Allow horizontal scroll if needed */
}

.d-flex {
  display: flex;
}

.justify-content-between {
  justify-content: space-between;
}

.align-items-center {
  align-items: center;
}
</style>
