<template>
  <div>
    <h2>Criar Conta</h2>
    
    <div v-if="Object.keys(form.errors).length > 0" style="background: red; color: white; padding: 10px;">
      <p>ERROS:</p>
      <pre>{{ form.errors }}</pre>
    </div>

    <form @submit.prevent="submit">
      <div>
        <label>Nome</label>
        <input
          v-model="form.name"
          type="text"
        />
        <span v-if="form.errors.name" style="color: red;">{{ form.errors.name }}</span>
      </div>

      <div>
        <label>Email</label>
        <input
          v-model="form.email"
          type="email"
        />
        <span v-if="form.errors.email" style="color: red;">{{ form.errors.email }}</span>
      </div>

      <div>
        <label>Senha</label>
        <input
          v-model="form.password"
          type="password"
        />
        <span v-if="form.errors.password" style="color: red;">{{ form.errors.password }}</span>
      </div>

      <div>
        <label>Confirmar Senha</label>
        <input
          v-model="form.password_confirmation"
          type="password"
        />
      </div>

      <button type="submit" :disabled="form.processing">
        {{ form.processing ? 'Criando conta...' : 'Registrar' }}
      </button>
    </form>

    <p>
      Já tem conta? 
      <a href="/login">Faça login</a>
    </p>
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