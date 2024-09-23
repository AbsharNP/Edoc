<template>
    <!--  BEGIN SIDEBAR  -->
    <div class="sidebar-wrapper sidebar-theme">
      <nav ref="menu" id="sidebar">
        <div class="shadow-bottom"></div>
  
        <!-- Sidebar Menu -->
        <perfect-scrollbar class="list-unstyled menu-categories" tag="ul" :options="{ wheelSpeed: 0.5, swipeEasing: true, minScrollbarLength: 40, maxScrollbarLength: 300, suppressScrollX: true }">
          
          <!-- Dashboard Menu Item -->
          <li class="menu">
            <a class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#dashboard" aria-controls="dashboard" aria-expanded="false">
              <div>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-home"
                >
                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                  <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>{{ $t('dashboard') }}</span>
              </div>
              <div>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-chevron-right"
                >
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
              </div>
            </a>
  
            <ul id="dashboard" class="collapse submenu list-unstyled" data-bs-parent="#sidebar">
              <li>
                <router-link to="/" @click="toggleMobileMenu">Deploy</router-link>
              </li>
              <li>
                <router-link to="/index2" @click="toggleMobileMenu">{{ $t('analytics') }}</router-link>
              </li>
            </ul>
          </li>
  
          <!-- Apps Menu Item -->
          <li class="menu">
            <a class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#apps" aria-controls="apps" aria-expanded="false">
              <div>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-cpu"
                >
                  <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                  <rect x="9" y="9" width="6" height="6"></rect>
                  <line x1="9" y1="1" x2="9" y2="4"></line>
                  <line x1="15" y1="1" x2="15" y2="4"></line>
                  <line x1="9" y1="20" x2="9" y2="23"></line>
                  <line x1="15" y1="20" x2="15" y2="23"></line>
                  <line x1="20" y1="9" x2="23" y2="9"></line>
                  <line x1="20" y1="14" x2="23" y2="14"></line>
                  <line x1="1" y1="9" x2="4" y2="9"></line>
                  <line x1="1" y1="14" x2="4" y2="14"></line>
                </svg>
                <span>{{ $t('apps') }}</span>
              </div>
              <div>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-chevron-right"
                >
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
              </div>
            </a>
  
            <ul id="apps" class="collapse submenu list-unstyled" data-bs-parent="#sidebar">
              <li>
                <router-link to="/apps/chat" @click="toggleMobileMenu">Chat</router-link>
              </li>
              <li>
                <router-link to="/apps/mailbox" @click="toggleMobileMenu">Mailbox</router-link>
              </li>
            </ul>
          </li>
  
          <!-- Invoice Menu Item -->
          <li class="menu">
            <a class="dropdown-toggle" href="#appInvoice" data-bs-toggle="collapse" aria-controls="appInvoice" aria-expanded="false">
              <div>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-file-text"
                >
                  <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                  <polyline points="13 2 13 9 20 9"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                  <line x1="10" y1="9" x2="9" y2="9"></line>
                </svg>
                <span>{{ $t('invoice') }}</span>
              </div>
              <div>
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 24 24"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  class="feather feather-chevron-right"
                >
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
              </div>
            </a>
  
            <ul id="appInvoice" class="collapse list-unstyled" data-bs-parent="#sidebar">
              <li>
                <router-link to="/apps/invoice/list" @click="toggleMobileMenu">List</router-link>
              </li>
              <li>
                <router-link to="/apps/invoice/add" @click="toggleMobileMenu">Add</router-link>
              </li>
            </ul>
          </li>
  
        </perfect-scrollbar>
      </nav>
    </div>
  </template>
  
  <script>
  export default {
    name: "SideBar",
    methods: {
      toggleMobileMenu() {
        // Logic to close the menu on mobile when an item is clicked
      },
    },
  };
  </script>
  
  <style scoped>
  /* Add your custom sidebar styles here */
  .sidebar-wrapper {
    width: 250px;
    background-color: #fff;
  }
  
  .menu {
    padding: 10px;
  }
  
  .menu a {
    color: #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    text-decoration: none;
  }
  
  .menu svg {
    margin-right: 10px;
  }
  
  .submenu {
    padding-left: 20px;
  }
  
  .submenu li {
    margin-bottom: 10px;
  }
  </style>
  