import api from "@/services/api";

const state = {
  tasks: [],
  currentTask: null,
  loading: false,
  error: null,
  filters: {
    status: "",
    priority: "",
  },
};

const getters = {
  allTasks: (state) => state.tasks,
  currentTask: (state) => state.currentTask,
  loading: (state) => state.loading,
  error: (state) => state.error,
  filters: (state) => state.filters,
  filteredTasks: (state) => {
    let filtered = state.tasks;

    if (state.filters.status) {
      filtered = filtered.filter(
        (task) => task.status === state.filters.status
      );
    }

    if (state.filters.priority) {
      filtered = filtered.filter(
        (task) => task.priority === state.filters.priority
      );
    }

    return filtered;
  },
};

const mutations = {
  SET_TASKS(state, tasks) {
    state.tasks = tasks;
  },

  SET_CURRENT_TASK(state, task) {
    state.currentTask = task;
  },

  ADD_TASK(state, task) {
    state.tasks.unshift(task);
  },

  UPDATE_TASK(state, updatedTask) {
    const index = state.tasks.findIndex((task) => task.id === updatedTask.id);
    if (index !== -1) {
      state.tasks.splice(index, 1, updatedTask);
    }
  },

  REMOVE_TASK(state, taskId) {
    state.tasks = state.tasks.filter((task) => task.id !== taskId);
  },

  SET_LOADING(state, loading) {
    state.loading = loading;
  },

  SET_ERROR(state, error) {
    state.error = error;
  },

  CLEAR_ERROR(state) {
    state.error = null;
  },

  SET_FILTERS(state, filters) {
    state.filters = { ...state.filters, ...filters };
  },

  CLEAR_FILTERS(state) {
    state.filters = {
      status: "",
      priority: "",
    };
  },
};

const actions = {
  async fetchTasks({ commit, state }) {
    try {
      commit("SET_LOADING", true);
      commit("CLEAR_ERROR");

      const params = {};
      if (state.filters.status) params.status = state.filters.status;
      if (state.filters.priority) params.priority = state.filters.priority;

      const response = await api.get("/tasks", { params });
      commit("SET_TASKS", response.data.data || response.data);

      return { success: true };
    } catch (error) {
      const errorMessage =
        error.response?.data?.message || "Erro ao carregar tarefas";
      commit("SET_ERROR", errorMessage);
      return { success: false, message: errorMessage };
    } finally {
      commit("SET_LOADING", false);
    }
  },

  async fetchTask({ commit }, taskId) {
    try {
      commit("SET_LOADING", true);
      commit("CLEAR_ERROR");

      const response = await api.get(`/tasks/${taskId}`);
      commit("SET_CURRENT_TASK", response.data);

      return { success: true, task: response.data };
    } catch (error) {
      const errorMessage =
        error.response?.data?.message || "Erro ao carregar tarefa";
      commit("SET_ERROR", errorMessage);
      return { success: false, message: errorMessage };
    } finally {
      commit("SET_LOADING", false);
    }
  },

  async createTask({ commit }, taskData) {
    try {
      commit("SET_LOADING", true);
      commit("CLEAR_ERROR");

      const response = await api.post("/tasks", taskData);
      commit("ADD_TASK", response.data.task);

      return { success: true, task: response.data.task };
    } catch (error) {
      const errorMessage =
        error.response?.data?.message || "Erro ao criar tarefa";
      commit("SET_ERROR", errorMessage);
      return {
        success: false,
        message: errorMessage,
        errors: error.response?.data,
      };
    } finally {
      commit("SET_LOADING", false);
    }
  },

  async updateTask({ commit }, { id, taskData }) {
    try {
      commit("SET_LOADING", true);
      commit("CLEAR_ERROR");

      const response = await api.put(`/tasks/${id}`, taskData);
      commit("UPDATE_TASK", response.data.task);

      return { success: true, task: response.data.task };
    } catch (error) {
      const errorMessage =
        error.response?.data?.message || "Erro ao atualizar tarefa";
      commit("SET_ERROR", errorMessage);
      return {
        success: false,
        message: errorMessage,
        errors: error.response?.data,
      };
    } finally {
      commit("SET_LOADING", false);
    }
  },

  async deleteTask({ commit }, taskId) {
    try {
      commit("SET_LOADING", true);
      commit("CLEAR_ERROR");

      await api.delete(`/tasks/${taskId}`);
      commit("REMOVE_TASK", taskId);

      return { success: true };
    } catch (error) {
      const errorMessage =
        error.response?.data?.message || "Erro ao excluir tarefa";
      commit("SET_ERROR", errorMessage);
      return { success: false, message: errorMessage };
    } finally {
      commit("SET_LOADING", false);
    }
  },

  setFilters({ commit, dispatch }, filters) {
    commit("SET_FILTERS", filters);
    dispatch("fetchTasks");
  },

  clearFilters({ commit, dispatch }) {
    commit("CLEAR_FILTERS");
    dispatch("fetchTasks");
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
