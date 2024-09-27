<template>
  <div>
    <h1>Users</h1>
    <ul>
      <li v-for="user in users" :key="user.email">
        {{ (un) }} - {{ user.email }}
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    
    return {
      users: [],
    un: '',
    ue: '',
    };
  },
  created() {
    this.fetchUsers();
  },
  methods: {
    async fetchUsers() {
      try {
        const response = await axios.get('/users');
        this.users = response.data;
        this.un=response.name;
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    },
  },
};
</script>

<style scoped>
ul {
  list-style-type: none;
  padding: 0;
}
li {
  margin: 10px 0;
}
</style>
