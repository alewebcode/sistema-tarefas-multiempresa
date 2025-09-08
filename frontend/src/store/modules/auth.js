//import axios from "axios";
import api from "@/services/api";

const state = {
  token: localStorage.getItem("token") || null,
  user: JSON.parse(localStorage.getItem("user")) || null,
  loading: false,
  error: null,
};

const getters = {
  isAuthenticated: (state) => !!state.token,
  currentUser: (state) => state.user,
  loading: (state) => state.loading,
  error: (state) => state.error,
};

const mutations = {
  SET_TOKEN(state, token) {
    state.token = token;
    if (token) {
      localStorage.setItem("token", token);
    } else {
      localStorage.removeItem("token");
    }
  },

  SET_USER(state, user) {
    state.user = user;
    if (user) {
      localStorage.setItem("user", JSON.stringify(user));
    } else {
      localStorage.removeItem("user");
    }
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
};

const actions = {
  async login({ commit }, credentials) {
    try {
      commit("SET_LOADING", true);
      commit("CLEAR_ERROR");

      const response = await api.post("/auth/login", credentials);

      console.log(response.data);
      const { token, user } = response.data;

      commit("SET_TOKEN", token);
      commit("SET_USER", user);

      return { success: true };
    } catch (error) {
      const errorMessage =
        error.response?.data?.error || "Email ou senha inválidos";
      commit("SET_ERROR", errorMessage);
      return { success: false, message: errorMessage };
    } finally {
      commit("SET_LOADING", false);
    }
  },

  async register({ commit }, userData) {
    try {
      commit("SET_LOADING", true);
      commit("CLEAR_ERROR");

      const response = await api.post("/auth/register", userData);
      const { token, user } = response.data;

      commit("SET_TOKEN", token);
      commit("SET_USER", user);

      return { success: true };
    } catch (error) {
      const errorMessage =
        error.response?.data?.message || "Erro ao registrar usuário";
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

  async logout({ commit }) {
    try {
      await api.post("/auth/logout");
    } catch (error) {
      console.error("Erro ao fazer logout:", error);
    } finally {
      commit("SET_TOKEN", null);
      commit("SET_USER", null);
      commit("CLEAR_ERROR");
    }
  },

  async fetchProfile({ commit }) {
    try {
      const response = await api.get("/auth/profile");
      commit("SET_USER", response.data.user);
      return { success: true };
    } catch (error) {
      return { success: false };
    }
  },
};

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions,
};
