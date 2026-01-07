<template>
  <div class="auth-container">
    <div class="auth-card">
      <h2 class="auth-title">Login</h2>
      
      <form @submit.prevent="submit" class="auth-form">
        <div class="form-group">
          <label class="form-label">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="form-input"
          />
          <span v-if="form.errors.email" class="error-message">{{ form.errors.email }}</span>
        </div>

        <div class="form-group">
          <label class="form-label">Senha</label>
          <input
            v-model="form.password"
            type="password"
            required
            class="form-input"
          />
          <span v-if="form.errors.password" class="error-message">{{ form.errors.password }}</span>
        </div>

        <div class="form-group-checkbox">
          <label class="checkbox-label">
            <input
              v-model="form.remember"
              type="checkbox"
              class="checkbox-input"
            />
            Lembrar-me
          </label>
        </div>

        <button type="submit" :disabled="form.processing" class="submit-button">
          {{ form.processing ? 'Entrando...' : 'Entrar' }}
        </button>
      </form>

      <p class="auth-link">
        Não tem conta? 
        <a href="/register" class="link">Registre-se</a>
      </p>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
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

.form-group-checkbox {
  display: flex;
  align-items: center;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: #2E2E2E;
  font-size: 0.95rem;
  cursor: pointer;
}

.checkbox-input {
  width: 18px;
  height: 18px;
  cursor: pointer;
  accent-color: #3FA796;
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