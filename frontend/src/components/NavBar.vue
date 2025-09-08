<template>
  <nav class="navbar">
    <div class="navbar-container">
      <div class="navbar-brand">
        <router-link to="/dashboard" class="brand-link">
          TaskManager
        </router-link>
      </div>

      <div class="navbar-info" v-if="currentUser">
        <span class="user-info">
          <strong>{{ currentUser.name }}</strong> -
          <span class="company-name">{{ currentUser.company.name }}</span>
        </span>
      </div>

      <div class="navbar-actions">
        <button @click="handleLogout" class="btn btn-danger">Sair</button>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  name: "NavBar",
  computed: {
    ...mapGetters("auth", ["currentUser"]),
  },
  methods: {
    ...mapActions("auth", ["logout"]),
    async handleLogout() {
      await this.logout();
      this.$router.push("/login");
    },
  },
};
</script>

<style scoped>
.navbar {
  background-color: #343a40;
  padding: 0;
  margin-bottom: 0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.navbar-container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 20px;
}

.navbar-brand .brand-link {
  color: white;
  font-size: 1.5rem;
  font-weight: bold;
  text-decoration: none;
}

.navbar-brand .brand-link:hover {
  color: #f8f9fa;
}

.navbar-info {
  flex: 1;
  text-align: center;
}

.user-info {
  color: #f8f9fa;
  font-size: 0.9rem;
}

.company-name {
  color: #28a745;
  font-weight: 500;
}

.navbar-actions {
  display: flex;
  gap: 10px;
  align-items: center;
}

.navbar-actions .btn {
  font-size: 0.875rem;
  padding: 8px 16px;
}

@media (max-width: 768px) {
  .navbar-container {
    flex-direction: column;
    gap: 10px;
    padding: 15px 20px;
  }

  .navbar-info {
    text-align: center;
    order: 2;
  }

  .navbar-actions {
    order: 3;
  }
}
</style>
