import axios from "axios";
import router from "../router"; // seu Vue Router
import store from "../store";

const api = axios.create({
  baseURL: "http://localhost:8000/api",
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

// Interceptor para adicionar token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Interceptor de resposta
let isRedirecting = false;

api.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error.response?.status;

    if (status === 401 && !isRedirecting) {
      const currentRoute = router.currentRoute.fullPath;

      if (currentRoute !== "/login") {
        isRedirecting = true;

        localStorage.removeItem("token");
        localStorage.removeItem("user");
        store.dispatch("auth/logout");

        // adiando o redirecionamento para evitar conflitos
        setTimeout(() => {
          router.replace("/login").finally(() => {
            isRedirecting = false;
          });
        }, 0);
      }
    }

    return Promise.reject(error);
  }
);

export default api;
