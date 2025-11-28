<template>
  <div>
    <h2>Login</h2>
    
    <form @submit.prevent="submit">
      <div>
        <label>Email</label>
        <input
          v-model="form.email"
          type="email"
          required
        />
        <span v-if="form.errors.email">{{ form.errors.email }}</span>
      </div>

      <div>
        <label>Senha</label>
        <input
          v-model="form.password"
          type="password"
          required
        />
        <span v-if="form.errors.password">{{ form.errors.password }}</span>
      </div>

      <div>
        <label>
          <input
            v-model="form.remember"
            type="checkbox"
          />
          Lembrar-me
        </label>
      </div>

      <button type="submit" :disabled="form.processing">
        {{ form.processing ? 'Entrando...' : 'Entrar' }}
      </button>
    </form>

    <p>
      Não tem conta? 
      <a href="/register">Registre-se</a>
    </p>
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