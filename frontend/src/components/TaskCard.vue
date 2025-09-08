<template>
  <div class="task-card">
    <div class="task-header">
      <h4 class="task-title">{{ task.title }}</h4>
      <div class="task-actions">
        <button @click="$emit('edit', task)" class="btn-action edit">✏️</button>
        <button @click="$emit('delete', task)" class="btn-action delete">
          🗑️
        </button>
      </div>
    </div>

    <div v-if="task.description" class="task-description">
      {{ task.description }}
    </div>

    <div class="task-meta">
      <div class="task-status">
        <label>Status:</label>
        <select
          :value="task.status"
          @change="$emit('status-change', task, $event.target.value)"
          class="status-select"
          :class="`status-${task.status}`"
        >
          <option value="pendente">Pendente</option>
          <option value="em_andamento">Em Andamento</option>
          <option value="concluida">Concluída</option>
        </select>
      </div>

      <div class="task-priority">
        <span class="priority-label">Prioridade:</span>
        <span :class="`priority-${task.priority}`" class="priority-badge">
          {{ getPriorityText(task.priority) }}
        </span>
      </div>
    </div>

    <div v-if="task.due_date" class="task-due-date">
      <span class="due-date-label">Prazo:</span>
      <span class="due-date" :class="{ overdue: isOverdue }">
        {{ formatDate(task.due_date) }}
      </span>
    </div>

    <div v-if="task.user" class="task-user">
      <span class="user-label">Responsável:</span>
      <span class="user-name">{{ task.user.name }}</span>
    </div>

    <div class="task-dates">
      <small class="text-muted">
        Criada em {{ formatDate(task.created_at) }}
      </small>
    </div>
  </div>
</template>

<script>
export default {
  name: "TaskCard",
  props: {
    task: {
      type: Object,
      required: true,
    },
  },
  computed: {
    isOverdue() {
      if (!this.task.due_date) return false;
      const today = new Date();
      const dueDate = new Date(this.task.due_date);
      return dueDate < today && this.task.status !== "concluida";
    },
  },
  methods: {
    getPriorityText(priority) {
      const priorities = {
        baixa: "Baixa",
        media: "Média",
        alta: "Alta",
      };
      return priorities[priority] || priority;
    },

    formatDate(date) {
      if (!date) return "";
      return new Date(date).toLocaleDateString("pt-BR");
    },
  },
};
</script>

<style scoped>
.task-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  border-left: 4px solid #007bff;
  transition: box-shadow 0.3s ease;
}

.task-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.task-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.task-title {
  margin: 0;
  color: #333;
  font-size: 1.2rem;
  flex: 1;
  margin-right: 10px;
}

.task-actions {
  display: flex;
  gap: 8px;
}

.btn-action {
  background: none;
  border: none;
  font-size: 16px;
  cursor: pointer;
  padding: 5px;
  border-radius: 4px;
  transition: background-color 0.2s;
}

.btn-action:hover {
  background-color: #f8f9fa;
}

.btn-action.delete:hover {
  background-color: #f8d7da;
}

.task-description {
  color: #666;
  margin-bottom: 15px;
  line-height: 1.5;
}

.task-meta {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin-bottom: 15px;
}

.task-status label {
  display: block;
  font-size: 12px;
  color: #666;
  margin-bottom: 5px;
  text-transform: uppercase;
  font-weight: 500;
}

.status-select {
  width: 100%;
  padding: 5px 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.status-select.status-pendente {
  border-left-color: #6c757d;
  border-left-width: 3px;
}

.status-select.status-em_andamento {
  border-left-color: #007bff;
  border-left-width: 3px;
}

.status-select.status-concluida {
  border-left-color: #28a745;
  border-left-width: 3px;
}

.task-priority {
  display: flex;
  flex-direction: column;
}

.priority-label {
  font-size: 12px;
  color: #666;
  margin-bottom: 5px;
  text-transform: uppercase;
  font-weight: 500;
}

.priority-badge {
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
  text-align: center;
}

.priority-baixa {
  background-color: #d4edda;
  color: #155724;
}

.priority-media {
  background-color: #fff3cd;
  color: #856404;
}

.priority-alta {
  background-color: #f8d7da;
  color: #721c24;
}

.task-due-date,
.task-user {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
  font-size: 14px;
}

.due-date-label,
.user-label {
  color: #666;
  font-weight: 500;
}

.due-date {
  font-weight: 500;
}

.due-date.overdue {
  color: #dc3545;
  font-weight: bold;
}

.user-name {
  color: #007bff;
  font-weight: 500;
}

.task-dates {
  padding-top: 15px;
  border-top: 1px solid #e9ecef;
  text-align: right;
}

.text-muted {
  color: #6c757d;
}

/* Card border colors based on priority */
.task-card {
  border-left-color: #007bff;
}

.task-card:has(.priority-alta) {
  border-left-color: #dc3545;
}

.task-card:has(.priority-media) {
  border-left-color: #ffc107;
}

.task-card:has(.priority-baixa) {
  border-left-color: #28a745;
}
</style>
