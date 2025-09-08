<template>
  <div class="task-form-container">
    <div class="form-header">
      <h2>{{ isEdit ? "Editar Tarefa" : "Nova Tarefa" }}</h2>
      <router-link to="/dashboard" class="btn btn-secondary">
        Voltar
      </router-link>
    </div>

    <div class="task-form-card">
      <div v-if="error" class="alert alert-danger">
        {{ error }}
      </div>
      <ValidationObserver ref="observer">
        <form @submit.prevent="onSubmit">
          <div class="form-group">
            <ValidationProvider
              name="Título"
              rules="required"
              v-slot="{ errors }"
              vid="title"
            >
              <label for="title">Título *</label>
              <input
                type="text"
                id="title"
                v-model="form.title"
                class="form-control"
                :class="{ 'is-invalid': errors.length }"
                maxlength="150"
              />
              <span v-if="errors[0]" class="error-message">{{
                errors[0]
              }}</span>
            </ValidationProvider>
          </div>

          <div class="form-group">
            <label for="description">Descrição</label>
            <textarea
              id="description"
              v-model="form.description"
              class="form-control"
              rows="4"
              :class="{ 'is-invalid': validationErrors.description }"
            ></textarea>
            <div v-if="validationErrors.description" class="invalid-feedback">
              {{ validationErrors.description[0] }}
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="status">Status</label>
              <select
                id="status"
                v-model="form.status"
                class="form-control"
                :class="{ 'is-invalid': validationErrors.status }"
              >
                <option value="pendente">Pendente</option>
                <option value="em_andamento">Em Andamento</option>
                <option value="concluida">Concluída</option>
              </select>
              <div v-if="validationErrors.status" class="invalid-feedback">
                {{ validationErrors.status[0] }}
              </div>
            </div>

            <div class="form-group">
              <label for="priority">Prioridade</label>
              <select
                id="priority"
                v-model="form.priority"
                class="form-control"
                :class="{ 'is-invalid': validationErrors.priority }"
              >
                <option value="baixa">Baixa</option>
                <option value="media">Média</option>
                <option value="alta">Alta</option>
              </select>
              <div v-if="validationErrors.priority" class="invalid-feedback">
                {{ validationErrors.priority[0] }}
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="due_date">Data Limite </label>
            <date-picker
              type="date"
              id="due_date"
              format="DD/MM/YYYY"
              v-model="form.due_date"
              class="my-datepicker"
              :class="{ 'is-invalid': validationErrors.due_date }"
              :min="today"
              lang="pt-br"
            />
            <div v-if="validationErrors.due_date" class="invalid-feedback">
              {{ validationErrors.due_date[0] }}
            </div>
          </div>

          <div class="form-actions">
            <router-link to="/dashboard" class="btn btn-secondary">
              Cancelar
            </router-link>
            <button type="submit" class="btn btn-primary" :disabled="loading">
              {{ loading ? "Salvando..." : isEdit ? "Atualizar" : "Criar" }}
            </button>
          </div>
        </form>
      </ValidationObserver>
    </div>
  </div>
</template>

<script>
import { ValidationProvider, ValidationObserver, extend } from "vee-validate";
import { mapGetters, mapActions } from "vuex";
import DatePicker from "vue2-datepicker";
import "vue2-datepicker/index.css";
import ptBR from "vue2-datepicker/locale/pt-br";
import { required } from "vee-validate/dist/rules";

extend("required", {
  ...required,
  message: "Informe o campo título",
});

export default {
  components: { DatePicker, ValidationProvider, ValidationObserver },
  name: "TaskForm",
  props: {
    id: {
      type: [String, Number],
      default: null,
    },
  },
  data() {
    return {
      form: {
        title: "",
        description: "",
        status: "pendente",
        priority: "media",
        due_date: null,
      },
      validationErrors: {},
      isEdit: false,
    };
  },
  computed: {
    ...mapGetters("tasks", ["currentTask", "loading", "error"]),
    today() {
      return new Date().toISOString().split("T")[0];
    },
  },
  methods: {
    ...mapActions("tasks", ["fetchTask", "createTask", "updateTask"]),

    async onSubmit() {
      this.validationErrors = {};

      const isValid = await this.$refs.observer.validate();
      if (!isValid) return;
      this.validationErrors = {};

      let result;
      if (this.isEdit) {
        result = await this.updateTask({
          id: this.id,
          taskData: this.form,
        });
      } else {
        result = await this.createTask(this.form);
      }

      if (result.success) {
        this.$router.push("/dashboard");
      } else if (result.errors) {
        this.validationErrors = result.errors;
      }
    },
  },

  async created() {
    if (this.id) {
      this.isEdit = true;
      const result = await this.fetchTask(this.id);

      if (result.success) {
        const task = result.task;
        this.form = {
          title: task.title,
          description: task.description || "",
          status: task.status,
          priority: task.priority,
          due_date: task.due_date ? new Date(task.due_date) : null,
        };
      } else {
        this.$router.push("/dashboard");
      }
    }

    this.lang = ptBR;
  },
};
</script>

<style scoped>
.task-form-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.form-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
}

.form-header h2 {
  color: #333;
  margin: 0;
}

.task-form-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
}

.my-datepicker {
  border: none !important;
  box-shadow: none !important; /* Também remove a sombra se houver */
}

.error-message {
  color: #dc3545; /* vermelho bootstrap */
  font-size: 14px;
  margin-top: 5px;
  display: block;
}
.form-control.is-invalid {
  border-color: #dc3545;
}

.invalid-feedback {
  color: #dc3545;
  font-size: 14px;
  margin-top: 5px;
}
</style>
