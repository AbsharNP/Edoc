import { createRouter, createWebHistory } from 'vue-router';
import Index from "./components/views/index.vue";
// import AppLayout from './components/Layouts/appLayout.vue'; 

const routes = [
  {
    path: "/",
    name: "index",
    component: Index,
    meta: { layout: "auth"},
  },

  {
    path: '/login',
    name: 'login',
    component: () =>
      import(
        /* webpackChunkName: "login" */ './components/views/pages/auth/login.vue'
      ),
      meta: { layout: 'auth' },
  },
  {
    path: '/SignUp',
    name: 'signup',
    component: () =>
      import(
        /* webpackChunkName: "signup" */ './components/views/pages/auth/SignUp.vue'
      ),
      meta: { layout: 'auth' },
  },
  {
    path: '/index',
    name: 'home',
    component: () =>
      import(
        /* webpackChunkName: "home" */ './components/views/index.vue'
      ),
    meta: { layout: "auth" },
  },

  //admin
  {
    path: '/admin',
    name: 'admin',
    component: () =>
      import(
        /* webpackChunkName: "adminhome" */ './components/views/pages/admin/adminHome.vue'
      ),
    meta: { layout: "app" },
  },

  {
    path: '/doctor-view',
    name: 'doctor-view',
    component: () =>
      import(
        /* webpackChunkName: "adminhome" */ './components/views/pages/admin/doctors.vue'
      ),
    meta: { layout: "app" },
  },

  {
    path: '/add-doctor',
    name: 'add-Doctor',
    component: () =>
      import(
        /* webpackChunkName: "adminhome" */ './components/views/pages/admin/addDoctor.vue'
      ),
    meta: { layout: "auth" },
  },

  {
    path: '/department',
    name: 'deparment',
    component: () =>
      import(
        /* webpackChunkName: "adminhome" */ './components/views/pages/admin/departments.vue'
      ),
    meta: { layout: "app" },
  },

  {
    path: '/schedule',
    name: 'schedule',
    component: () =>
      import(
        /* webpackChunkName: "adminhome" */ './components/views/pages/admin/schedule.vue'
      ),
    meta: { layout: "app" },
  },



  {
    path: '/doctor-dash',
    name: 'doctor-dash',
    component: () =>
      import(
        /* webpackChunkName: "dochome" */ './components/views/pages/doctor/docHome.vue'
      ),
    meta: { layout: "app" },
  },

  {
    path: '/patient',
    name: 'patient',
    component: () =>
      import(
        /* webpackChunkName: "patienthome" */ './components/views/pages/patient/patientHome.vue'
      ),
    meta: { layout: "app" },
  },
  
];

// Create the router instance
const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
