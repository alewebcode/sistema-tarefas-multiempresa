<template>
  <div class="login-card">
    <h2>Login</h2>
    <p class="subtitle">Acesse sua conta do TaskManager</p>

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <ValidationObserver ref="observer" v-slot="{ handleSubmit }">
      <form @submit.prevent="handleSubmit(onSubmit)">
        <div class="form-group">
          <label for="email">Email</label>
          <ValidationProvider
            name="Email"
            rules="required|email"
            v-slot="{ errors }"
            vid="email"
          >
            <input
              type="email"
              id="email"
              v-model="form.email"
              class="form-control"
              :class="{ 'is-invalid': errors.length }"
            />
            <span v-if="errors[0]" class="error-message">{{ errors[0] }}</span>
          </ValidationProvider>
        </div>

        <div class="form-group">
          <label for="password">Senha</label>
          <ValidationProvider
            name="Senha"
            rules="required"
            v-slot="{ errors }"
            vid="password"
          >
            <input
              type="password"
              id="password"
              v-model="form.password"
              class="form-control"
              :class="{ 'is-invalid': errors.length }"
            />
            <span v-if="errors[0]" class="error-message">{{ errors[0] }}</span>
          </ValidationProvider>
        </div>

        <button type="submit" class="btn btn-primary btn-block">
          {{ loading ? "Entrando..." : "Entrar" }}
        </button>
      </form>
    </ValidationObserver>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { required, email } from "vee-validate/dist/rules";

extend("required", {
  ...required,
  message: "Este campo é obrigatório.",
});

extend("email", {
  ...email,
  message: "Por favor, insira um email válido.",
});

export default {
  components: { ValidationProvider, ValidationObserver },
  name: "LoginView",
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
      validationErrors: {},
    };
  },
  computed: {
    ...mapGetters("auth", ["loading", "error"]),
  },
  methods: {
    ...mapActions("auth", ["login"]),

    async onSubmit() {
      const isValid = await this.$refs.observer.validate();
      if (!isValid) return;

      this.validationErrors = {};
      const result = await this.login(this.form);
      if (result.success) this.$router.push("/dashboard");
      else if (result.errors) this.validationErrors = result.errors;
    },
  },
};
</script>

<style scoped>
.login-container {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
}

.login-card {
  background: white;
  padding: 40px;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  width: 100%;
  max-width: 400px;
}

.login-card h2 {
  text-align: center;
  margin-bottom: 10px;
  color: #333;
}

.subtitle {
  text-align: center;
  color: #666;
  margin-bottom: 30px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 500;
  color: #555;
}

.form-control {
  width: 100%;
  padding: 12px;
  border: 2px solid #e1e1e1;
  border-radius: 6px;
  font-size: 16px;
  transition: border-color 0.3s;
  box-sizing: border-box;
}

.form-control:focus {
  border-color: #007bff;
  outline: none;
}

.form-control.is-invalid {
  border-color: #dc3545;
}

.invalid-feedback {
  color: #dc3545;
  font-size: 14px;
  margin-top: 5px;
}

.btn-block {
  width: 100%;
}

.btn-primary {
  background-color: #007bff;
  border-color: #007bff;
  padding: 12px;
  font-size: 16px;
  font-weight: 500;
}

.btn-primary:disabled {
  background-color: #6c757d;
  border-color: #6c757d;
  cursor: not-allowed;
}

.register-link {
  text-align: center;
  margin-top: 25px;
  padding-top: 20px;
  border-top: 1px solid #e1e1e1;
}

.register-link p {
  color: #666;
  margin: 0;
}

.register-link a {
  color: #007bff;
  text-decoration: none;
  font-weight: 500;
}

.register-link a:hover {
  text-decoration: underline;
}
.error-message {
  color: #dc3545; /* vermelho bootstrap */
  font-size: 14px;
  margin-top: 5px;
  display: block;
}
</style>
