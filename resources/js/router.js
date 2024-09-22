import { createRouter, createWebHistory } from 'vue-router';
import Index from "./components/views/index.vue";
import AppLayout from './components/Layouts/appLayout.vue'; 

const routes = [
  {
    path: "/",
    name: "index",
    component: Index,
    meta: { layout: "auth"},
  },

  {
    path: '/signin',
    name: 'login-page',
    component: () =>
      import(
        /* webpackChunkName: "login" */ './components/Auth/Login.vue'
      ),
      meta: { layout: 'auth' },
  },
  {
    path: '/SignUp',
    name: 'signup',
    component: () =>
      import(
        /* webpackChunkName: "signup" */ './components/Auth/SignUp.vue'
      ),
      meta: { layout: 'auth' },
  },
  // {
  //   path: '/home',
  //   name: 'home',
  //   component: () =>
  //     import(
  //       /* webpackChunkName: "home" */ './components/views/index.vue'
  //     ),
  // }
];

// Create the router instance
const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
