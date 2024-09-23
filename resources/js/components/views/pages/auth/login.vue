<template>
  <section class="vh-100">
    <div class="container py-5 h-100">
      <div class="row d-flex align-items-center justify-content-center h-100">
        <div class="col-md-8 col-lg-7 col-xl-6">
          <object type="image/svg+xml" data="/svg/login.svg" class="img-fluid" alt="Phone image" />
        </div>
        <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
          <form @submit.prevent="login">
            <div class="form-outline mb-4">
              <input type="email" v-model="email" class="form-control form-control-lg" required />
              <label class="form-label" for="form1Example13">Email address</label>
              <div v-if="errors.email" class="text-danger">{{ errors.email }}</div>
            </div>

            <div class="form-outline mb-4">
              <input type="password" v-model="password" class="form-control form-control-lg" required />
              <label class="form-label" for="form1Example23">Password</label>
              <div v-if="errors.password" class="text-danger">{{ errors.password }}</div>
            </div>

            <div class="d-flex justify-content-around align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="form1Example3" v-model="remember" />
                <label class="form-check-label" for="form1Example3"> Remember me </label>
              </div>
              <a href="#">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>

            <p class="p-3">Create an account? <router-link to="/SignUp">Register</router-link></p>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LoginPage',
  data() {
    return {
      email: '',
      password: '',
      remember: false,
      errors: {},
    };
  },
  methods: {
    async login() {
      this.errors = {};

      try {
        const response = await axios.post('/login', {
          email: this.email,
          password: this.password,
          remember: this.remember,
        });

        const userType = response.data.user.user_type;

        // Redirect based on user type
        if (userType === 'admin') {
          this.$router.push('/admin');
        } else if (userType === 'patient') {
          this.$router.push('/patient');
        } else if (userType === 'doctor') {
          this.$router.push('/doctor');
        }
      } catch (error) {
        if (error.response && error.response.data.errors) {
          // Handle validation errors
          this.errors = error.response.data.errors;
        } else {
          alert('Login failed. Please try again.');
        }
      }
    },
  },
};
</script>