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
    meta: { layout: "app" , requiresAuth: true, userType: 'admin'},
  },

  {
    path: '/doctor-view',
    name: 'doctor-view',
    component: () =>
      import(
        /* webpackChunkName: "adminhome" */ './components/views/pages/admin/doctors.vue'
      ),
    meta: { layout: "app" , requiresAuth: true, userType: 'admin'},
  },

  {
    path: '/add-doctor',
    name: 'add-Doctor',
    component: () =>
      import(
        /* webpackChunkName: "adminhome" */ './components/views/pages/admin/addDoctor.vue'
      ),
    meta: { layout: "auth" , requiresAuth: true, userType: 'admin'},
  },

  {
    path: '/department',
    name: 'deparment',
    component: () =>
      import(
        /* webpackChunkName: "adminhome" */ './components/views/pages/admin/departments.vue'
      ),
    meta: { layout: "app" , requiresAuth: true, userType: 'admin'},
  },



  //doctor
  {
    path: '/doctor-dash',
    name: 'doctor-dash',
    component: () =>
      import(
        /* webpackChunkName: "dochome" */ './components/views/pages/doctor/docHome.vue'
      ),
    meta: { layout: "app" , requiresAuth: true, userType: 'doctor'},
  },
  {
    path: '/schedule-sess',
    name: 'schedule-sess',
    component: () =>
      import(
        /* webpackChunkName: "dochome" */ './components/views/pages/doctor/schedule.vue'
      ),
      meta: { layout: "app" , requiresAuth: true, userType: 'doctor'},
    },
  {
    path: '/appointment',
    name: 'appointment',
    component: () =>
      import(
        /* webpackChunkName: "dochome" */ './components/views/pages/doctor/d_appointments.vue'
      ),
      meta: { layout: "app" , requiresAuth: true, userType: 'doctor'},
  },

  {
    path: '/my-patients',
    name: 'my-patients',
    component: () =>
      import(
        /* webpackChunkName: "dochome" */ './components/views/pages/doctor/my_patients.vue'
      ),
      meta: { layout: "app" , requiresAuth: true, userType: 'doctor'},
  },
  {
    path: '/treatment/:id',
    name: 'treatment',
    component: () =>
      import(
        /* webpackChunkName: "dochome" */ './components/views/pages/doctor/treatment.vue'
      ),
      meta: { layout: "app" , requiresAuth: true, userType: 'doctor'},
  },
  {
    path: '/treatment-completed',
    name: 'treatmented',
    component: () =>
      import(
        /* webpackChunkName: "dochome" */ './components/views/pages/doctor/treated_patients.vue'
      ),
      meta: { layout: "app" , requiresAuth: true, userType: 'doctor'},
  },
  
  

  //patients

  {
    path: '/patient-dash',
    name: 'patient-dash',
    component: () =>
      import(
        /* webpackChunkName: "patienthome" */ './components/views/pages/patient/patientHome.vue'
      ),
    meta: { layout: "app" },
  },
  {
    path: '/doc-list',
    name: 'doc-list',
    component: () =>
      import(
        /* webpackChunkName: "patienthome" */ './components/views/pages/patient/p_doctors.vue'
      ),
    meta: { requiresAuth: true, userType: 'patient'},
  },
  {
    path: '/my-appointment',
    name: 'my-appointment',
    component: () =>
      import(
        /* webpackChunkName: "patienthome" */ './components/views/pages/patient/p_appointment.vue'
      ),
      meta: { layout: "app" ,requiresAuth: true, userType: 'patient'},
  },
  {
    path: '/available-sessions',
    name: 'available-sessions',
    component: () =>
      import(
        /* webpackChunkName: "patienthome" */ './components/views/pages/patient/session.vue'
      ),
      meta: { layout: "app" ,requiresAuth: true, userType: 'patient'},
    },
  {
    path: '/view-prescriptions',
    name: 'viewprescription',
    component: () =>
      import(
        /* webpackChunkName: "patienthome" */ './components/views/pages/patient/view_prescription.vue'
      ),
      meta: { layout: "app" ,requiresAuth: true, userType: 'patient'},
    },
  
  
];


const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('isAuthenticated'); 
  const userType = localStorage.getItem('userType'); 

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
      next({ name: 'login' }); 
    } else if (to.meta.userType && to.meta.userType !== userType) {
      next({ name: 'login' }); 
    } else {
      next(); 
    }
  } else {
    next();
  }
});

export default router;
