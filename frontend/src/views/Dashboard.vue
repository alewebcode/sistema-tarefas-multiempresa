<template>
  <div class="dashboard">
    <div class="dashboard-header">
      <h1>Dashboard</h1>
      <p v-if="currentUser">
        Bem-vindo(a), {{ currentUser.name }}! Gerencie as tarefas da
        {{ currentUser.company.name }}.
      </p>
    </div>

    <!-- Filtros -->
    <div class="filters-section card">
      <h3>Filtros</h3>
      <div class="filters-grid">
        <div class="filter-group">
          <label for="status-filter">Status:</label>
          <select
            id="status-filter"
            v-model="localFilters.status"
            @change="applyFilters"
            class="form-control"
          >
            <option value="">Todos</option>
            <option value="pendente">Pendente</option>
            <option value="em_andamento">Em Andamento</option>
            <option value="concluida">Concluída</option>
          </select>
        </div>

        <div class="filter-group">
          <label for="priority-filter">Prioridade:</label>
          <select
            id="priority-filter"
            v-model="localFilters.priority"
            @change="applyFilters"
            class="form-control"
          >
            <option value="">Todas</option>
            <option value="baixa">Baixa</option>
            <option value="media">Média</option>
            <option value="alta">Alta</option>
          </select>
        </div>

        <div class="filter-actions">
          <button @click="clearFilter" class="btn btn-warning">
            Limpar Filtros
          </button>
        </div>
      </div>
    </div>

    <!-- Lista de Tarefas -->
    <div class="tasks-section">
      <div class="section-header">
        <h3>Suas Tarefas</h3>
        <router-link to="/tasks/create" class="btn btn-success">
          Nova Tarefa
        </router-link>
      </div>

      <div v-if="loading" class="loading">Carregando tarefas...</div>

      <div v-else-if="error" class="alert alert-danger">
        {{ error }}
      </div>

      <div v-else-if="filteredTasks.length === 0" class="no-tasks">
        <p>Nenhuma tarefa encontrada.</p>
        <router-link to="/tasks/create" class="btn btn-primary">
          Criar sua primeira tarefa
        </router-link>
      </div>

      <div v-else class="tasks-grid">
        <TaskCard
          v-for="task in filteredTasks"
          :key="task.id"
          :task="task"
          @edit="editTask"
          @delete="confirmDelete"
          @status-change="updateTaskStatus"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import TaskCard from "../components/TaskCard.vue";
import Swal from "sweetalert2";

export default {
  name: "DashboardView",
  components: {
    TaskCard,
  },
  data() {
    return {
      localFilters: {
        status: "",
        priority: "",
      },
    };
  },
  computed: {
    ...mapGetters("auth", ["currentUser"]),
    ...mapGetters("tasks", ["filteredTasks", "loading", "error", "filters"]),
  },
  methods: {
    ...mapActions("tasks", [
      "fetchTasks",
      "setFilters",
      "clearFilters",
      "deleteTask",
      "updateTask",
    ]),

    applyFilters() {
      this.setFilters(this.localFilters);
    },

    clearFilter() {
      console.log("chego aqui");
      this.localFilters = {
        status: "",
        priority: "",
      };
      this.clearFilters();
    },

    editTask(task) {
      this.$router.push(`/tasks/${task.id}/edit`);
    },
    confirmDelete(task) {
      Swal.fire({
        title: "Tem certeza?",
        text: "Você não poderá reverter esta ação!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteItem(task);
        }
      });
    },

    async deleteItem(task) {
      console.log(task.id);
      const result = await this.deleteTask(task.id);
      if (result.success) {
        console.log("removido");
        // Tarefa já foi removida do store pelo action
      }
    },

    async updateTaskStatus(task, newStatus) {
      const result = await this.updateTask({
        id: task.id,
        taskData: { ...task, status: newStatus },
      });

      if (!result.success) {
        alert("Erro ao atualizar status da tarefa");
      }
    },
  },

  async created() {
    await this.fetchTasks();
    this.localFilters = { ...this.filters };
  },
};
</script>

<style scoped>
.dashboard {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.dashboard-header {
  text-align: center;
  margin-bottom: 30px;
}

.dashboard-header h1 {
  color: #333;
  margin-bottom: 10px;
}

.dashboard-header p {
  color: #666;
  font-size: 1.1rem;
}

.filters-section {
  margin-bottom: 30px;
}

.filters-section h3 {
  margin-bottom: 20px;
  color: #495057;
}

.filters-grid {
  display: grid;
  grid-template-columns: 1fr 1fr auto;
  gap: 20px;
  align-items: end;
}

.filter-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
  color: #555;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.section-header h3 {
  color: #495057;
  margin: 0;
}

.loading {
  text-align: center;
  padding: 40px;
  color: #666;
  font-size: 1.1rem;
}

.no-tasks {
  text-align: center;
  padding: 40px;
}

.no-tasks p {
  color: #666;
  font-size: 1.1rem;
  margin-bottom: 20px;
}

.tasks-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 20px;
}

@media (max-width: 768px) {
  .filters-grid {
    grid-template-columns: 1fr;
    gap: 15px;
  }

  .filter-actions {
    text-align: center;
  }

  .section-header {
    flex-direction: column;
    gap: 15px;
    align-items: stretch;
  }

  .tasks-grid {
    grid-template-columns: 1fr;
  }
}
</style>
