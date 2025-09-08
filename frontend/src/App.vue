<template>
  <div id="app">
    <NavBar v-if="isAuthenticated" />
    <main
      class="container"
      :class="{ 'mt-4': isAuthenticated, 'full-height': !isAuthenticated }"
    >
      <router-view />
    </main>
  </div>
</template>

<script>
import NavBar from "./components/NavBar.vue";
import { mapGetters } from "vuex";

export default {
  name: "App",
  components: {
    NavBar,
  },
  computed: {
    ...mapGetters("auth", ["isAuthenticated"]),
  },
  created() {
    // Verificar se há token salvo no localStorage
    const token = localStorage.getItem("token");
    const user = localStorage.getItem("user");

    if (token && user) {
      this.$store.commit("auth/SET_TOKEN", token);
      this.$store.commit("auth/SET_USER", JSON.parse(user));
    }
  },
};
</script>

<style>
#app {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.full-height {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn {
  padding: 10px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
  text-decoration: none;
  display: inline-block;
  text-align: center;
  transition: background-color 0.3s;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.btn-success {
  background-color: #28a745;
  color: white;
}

.btn-success:hover {
  background-color: #1e7e34;
}

.btn-danger {
  background-color: #dc3545;
  color: white;
}

.btn-danger:hover {
  background-color: #c82333;
}

.btn-warning {
  background-color: #ffc107;
  color: #212529;
}

.btn-warning:hover {
  background-color: #e0a800;
}

.form-group {
  margin-bottom: 1rem;
}

.form-control {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
  font-size: 1rem;
}

.form-control:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.alert {
  padding: 0.75rem 1.25rem;
  margin-bottom: 1rem;
  border: 1px solid transparent;
  border-radius: 0.25rem;
}

.alert-danger {
  color: #721c24;
  background-color: #f8d7da;
  border-color: #f5c6cb;
}

.alert-success {
  color: #155724;
  background-color: #d4edda;
  border-color: #c3e6cb;
}

.card {
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin-bottom: 20px;
}

.badge {
  padding: 4px 8px;
  font-size: 12px;
  border-radius: 12px;
  color: white;
  text-transform: uppercase;
}

.badge-pendente {
  background-color: #6c757d;
}

.badge-em_andamento {
  background-color: #007bff;
}

.badge-concluida {
  background-color: #28a745;
}

.priority-baixa {
  color: #28a745;
}

.priority-media {
  color: #ffc107;
}

.priority-alta {
  color: #dc3545;
}
</style>
