<template>
  <div class="auth-container">
    <div class="auth-card">
      <h2 class="auth-title">Criar Conta</h2>
      
      <div v-if="Object.keys(form.errors).length > 0" class="error-box">
        <p class="error-title">Erros encontrados:</p>
        <ul class="error-list">
          <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
        </ul>
      </div>

      <form @submit.prevent="submit" class="auth-form">
        <div class="form-group">
          <label class="form-label">Nome</label>
          <input
            v-model="form.name"
            type="text"
            class="form-input"
          />
          <span v-if="form.errors.name" class="error-message">{{ form.errors.name }}</span>
        </div>

        <div class="form-group">
          <label class="form-label">Email</label>
          <input
            v-model="form.email"
            type="email"
            class="form-input"
          />
          <span v-if="form.errors.email" class="error-message">{{ form.errors.email }}</span>
        </div>

        <div class="form-group">
          <label class="form-label">Senha</label>
          <input
            v-model="form.password"
            type="password"
            class="form-input"
          />
          <span v-if="form.errors.password" class="error-message">{{ form.errors.password }}</span>
        </div>

        <div class="form-group">
          <label class="form-label">Confirmar Senha</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            class="form-input"
          />
        </div>

        <button type="submit" :disabled="form.processing" class="submit-button">
          {{ form.processing ? 'Criando conta...' : 'Registrar' }}
        </button>
      </form>

      <p class="auth-link">
        Já tem conta? 
        <a href="/login" class="link">Faça login</a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  form.post('/register', {
  });
};
</script>

<style scoped>
.auth-container {
  min-height: 100vh;
  background-color: #E6F2EF;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 2rem;
}

.auth-card {
  background-color: white;
  border-radius: 12px;
  padding: 3rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 450px;
}

.auth-title {
  color: #2E2E2E;
  font-size: 2rem;
  font-weight: 600;
  margin-bottom: 2rem;
  text-align: center;
}

.error-box {
  background-color: #fee;
  border: 2px solid #e74c3c;
  border-radius: 8px;
  padding: 1rem;
  margin-bottom: 1.5rem;
}

.error-title {
  color: #c0392b;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.error-list {
  margin: 0;
  padding-left: 1.5rem;
  color: #e74c3c;
  font-size: 0.9rem;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-label {
  color: #2E2E2E;
  font-size: 0.95rem;
  font-weight: 500;
}

.form-input {
  padding: 0.75rem 1rem;
  border: 2px solid #E6F2EF;
  border-radius: 8px;
  font-size: 1rem;
  color: #2E2E2E;
  transition: border-color 0.3s ease;
}

.form-input:focus {
  outline: none;
  border-color: #3FA796;
}

.error-message {
  color: #e74c3c;
  font-size: 0.85rem;
  margin-top: 0.25rem;
}

.submit-button {
  background-color: #3FA796;
  color: white;
  padding: 0.875rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.1s ease;
  margin-top: 0.5rem;
}

.submit-button:hover {
  background-color: #35917f;
}

.submit-button:active {
  transform: scale(0.98);
}

.submit-button:disabled {
  background-color: #a0c4bd;
  cursor: not-allowed;
}

.auth-link {
  text-align: center;
  margin-top: 1.5rem;
  color: #2E2E2E;
  font-size: 0.95rem;
}

.link {
  color: #3FA796;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.3s ease;
}

.link:hover {
  color: #35917f;
  text-decoration: underline;
}
</style>