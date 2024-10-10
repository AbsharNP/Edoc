<template>
    <div class="d-flex">
      <div class="sidebar bg-light vh-100 p-0">
        <PatSidebar />
      </div>
  
      <div class="container">
        <div class="user-profile-update">
          <div class="profile-section">
            <h2>Update Profile</h2>
  
            <div class="profile-pic-container">
              <img
                :src="previewImage || '/path/to/user-default.png'"
                alt="Profile Preview"
                class="profile-preview"
              />
            </div>
  
            <form @submit.prevent="updateProfile">
              <div class="form-group">
                <label for="profile_pic">Update Profile Picture</label>
                <input
                  type="file"
                  @change="handleFileChange"
                  id="profile_pic"
                  accept="image/*"
                />
              </div>
  
              <div class="form-group">
                <label for="name">Name</label>
                <input type="text" v-model="form.name" id="name" required />
              </div>
  
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" v-model="form.email" id="email" required />
              </div>
  
              <div class="form-group">
                <label for="password">New Password</label>
                <input
                  type="password"
                  v-model="form.password"
                  id="password"
                  placeholder="Leave blank to keep current password"
                />
              </div>
  
              <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import Swal from 'sweetalert2';
  import PatSidebar from '../../../layoutComponents/patientSidebar.vue';
  
  export default {
    components: {
      PatSidebar,
    },
    data() {
      return {
        form: {
          name: '',
          email: '',
          profile_pic: null,
          password: '',
        },
        previewImage: null, 
      };
    },
    methods: {
      handleFileChange(event) {
        const file = event.target.files[0];
        this.form.profile_pic = file;
  
        if (file) {
          this.previewImage = URL.createObjectURL(file);
        }
      },
  
      updateProfile() {
        const formData = new FormData();
        formData.append('name', this.form.name);
        formData.append('email', this.form.email);
        if (this.form.profile_pic) {
          formData.append('profile_pic', this.form.profile_pic);
        }
        if (this.form.password) {
          formData.append('password', this.form.password);
        }
  
        axios
          .post('/user/update-profile', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })
          .then(() => {
            Swal.fire({
              icon: 'success',
              title: 'Profile updated successfully!',
              confirmButtonText: 'OK',
            });
          })
          .catch((error) => {
            console.error('Error updating profile:', error);
            Swal.fire({
              icon: 'error',
              title: 'Failed to update profile',
              text: error.response?.data?.message || 'Something went wrong!',
              confirmButtonText: 'Try Again',
            });
          });
      },
    },
    mounted() {
      axios
        .post('/user/profile')
        .then((response) => {
          const user = response.data;
          this.form.name = user.name;
          this.form.email = user.email;
          this.previewImage = user.profile_pic ? `/storage/${user.profile_pic}` : '/images/default-avatar.jpg';

        })
        .catch((error) => {
          console.error('Error fetching profile:', error);
        });
    },
  };
  </script>
  
  <style scoped>
  .container {
    display: flex;
  }
  
  .sidebar {
    width: 250px;
    flex-shrink: 0;
  }
  
  .user-profile-update {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    width: 100%;
    padding: 20px;
  }
  
  .profile-section {
    text-align: center;
    max-width: 600px;
    width: 100%;
    margin: auto;
  }
  
  .profile-pic-container {
    margin-bottom: 20px;
  }
  
  .profile-preview {
    display: block;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    margin: auto;
  }
  
  .form-group {
    margin-bottom: 15px;
    text-align: left;
  }
  
  label {
    display: block;
    margin-bottom: 5px;
  }
  
  input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
  }
  
  button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    width: 100%;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  </style>
  