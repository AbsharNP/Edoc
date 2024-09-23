<template>
  <section class="vh-100" style="background-color: #eee;">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-12 col-xl-11">
          <div class="card text-black" style="border-radius: 25px;">
            <div class="card-body p-md-5">
              <div class="row justify-content-center">
                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                  <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>

                  <form class="mx-1 mx-md-4" @submit.prevent="handleSubmit">

                    <!-- Name Input -->
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <input type="text" id="form3Example1c" class="form-control" v-model="name" />
                        <label class="form-label" for="form3Example1c">Your Name</label>
                        <div v-if="errors.name" class="text-danger">{{ errors.name[0] }}</div>
                      </div>
                    </div>

                    <!-- Email Input -->
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <input type="email" id="form3Example3c" class="form-control" v-model="email"/>
                        <label class="form-label" for="form3Example3c">Your Email</label>
                        <div v-if="errors.email" class="text-danger">{{ errors.email[0] }}</div>
                      </div>
                    </div>

                    <!-- Password Input -->
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <input type="password" v-model="password" id="form3Example4c" class="form-control" />
                        <label class="form-label" for="form3Example4c">Password</label>
                        <div v-if="errors.password" class="text-danger">{{ errors.password[0] }}</div>
                      </div>
                    </div>

                    <!-- Repeat Password Input -->
                    <div class="d-flex flex-row align-items-center mb-4">
                      <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                      <div class="form-outline flex-fill mb-0">
                        <input type="password" id="form3Example4cd" class="form-control" v-model="repeatpassword"/>
                        <label class="form-label" for="form3Example4cd">Repeat your password</label>
                        <div v-if="errors.repeatpassword" class="text-danger">{{ errors.repeatpassword }}</div>
                      </div>
                    </div>

                    <div class="form-check d-flex justify-content-center mb-5">
                      <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" />
                      <label class="form-check-label" for="form2Example3" required>
                        I agree to all statements in <a href="#!">Terms of service</a>
                      </label>
                    </div>

                    <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                      <button type="submit" class="btn btn-primary btn-lg">Register</button>
                    </div>
                    <p class="p-3">Already have an account? <router-link to="/login">Sign In</router-link></p>

                  </form>
                </div>
                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                  <object type="image/svg+xml" data="/svg/signup.svg" class="img-fluid" alt="Phone image" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      name: '',
      email: '',
      password: '',
      repeatpassword: '',
      errors: {}, // For field-specific errors
    };
  },
  methods: {
    async handleSubmit() {
      this.clearErrors(); // Clear previous errors

      if (this.password !== this.repeatpassword) {
        this.errors.repeatpassword = 'Passwords do not match';
        return;
      }

      const data = {
        name: this.name,
        email: this.email,
        password: this.password,
        password_confirmation: this.repeatpassword,
      };

      try {
        const response = await axios.post('/register', data);
        alert('Registration successful!');
        this.$router.push('/login');
      } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
          this.errors = error.response.data.errors; // Server-side validation errors
        } else {
          alert('Registration failed: ' + error.message);
        }
      }
    },
    clearErrors() {
      this.errors = {}; // Clear all errors
    }
  }
};
</script>
