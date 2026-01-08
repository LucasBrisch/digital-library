<template>
    <Header />

    <div class="page-container">
        <h1 class="page-title">Amigos</h1>

        <div class="search-section">
            <input 
                type="text" 
                class="search-input" 
                placeholder="Encontrar pessoas" 
                v-model="searchQuery"
            >

            <div v-if="searchQuery" class="search-results">
                <div v-if="searchedUsers.length > 0">
                    <div v-for="user in searchedUsers" :key="user.id" class="search-result-item">
                        <span class="user-name">{{ user.name }}</span>
                        <span v-if="isFriend(user)" class="tag tag-friend">Amigo</span>
                        <span v-else-if="isPending(user)" class="tag tag-pending">Pendente</span>
                        <button v-else @click="addfriend(user.id)" class="add-button">Adicionar</button>
                    </div>
                </div>
                <div v-else class="search-no-results">
                    Nenhum usuário encontrado.
                </div>
            </div>
        </div>

        <div class="friends-grid">
            <div class="friends-card card">
                <h3 class="card-title">Amigos</h3>
                <div v-if="friends.length > 0" class="user-list">
                    <div v-for="user in friends" :key="user.id" class="user-item">
                        <span class="user-name">{{ user.name }} <button @click="">Ver Perfil</button> <button @click="remove(user.id)">remover</button></span>
                    </div>
                </div>
                <div v-else class="empty-message">Nenhum amigo ainda</div>
            </div>

            <div class="pending-card card">
                <h3 class="card-title">Pedidos Pendentes</h3>
                <div v-if="pendings.length > 0" class="user-list">
                    <div v-for="user in pendings" :key="user.id" class="user-item">
                        <span class="user-name">{{ user.name }}  <button @click="remove(user.id)">remover</button></span>
                    </div>
                </div>
                <div v-else class="empty-message">Nenhum pedido pendente</div>
            </div>

            <div class="received-card card">
                <h3 class="card-title">Pedidos Recebidos</h3>
                <div v-if="received.length > 0" class="user-list">
                    <div v-for="user in received" :key="user.id" class="user-item-actions">
                        <span class="user-name">{{ user.name }}</span>
                        <div class="action-buttons">
                            <button @click="accept(user.id)" class="accept-button">Aceitar</button>
                            <button @click="refuse(user.id)" class="refuse-button">Recusar</button>
                        </div>
                    </div>
                </div>
                <div v-else class="empty-message">Nenhum pedido recebido</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import Header from '../components/header.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps ({
    users: Array,
    allusers : Array
})

const friends = computed(() => {
    return props.users.filter(user => user.status === 'friend');
});

const pendings = computed (() => {
    return props.users.filter(user => user.status === 'pending');
})

const received = computed (() => {
    return props.users.filter(user => user.status === 'received');
})

const isFriend = (user) => {
    return friends.value.some(friend => friend.id === user.id);
}

const isPending = (user) => {
    return pendings.value.some(pendingUser => pendingUser.id === user.id);
}

const searchQuery = ref('');

const searchedUsers = computed(() => {
  if (!searchQuery.value) {
    return []; 
  }
  return props.allusers.filter(user =>
    user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const addfriend = (id) => {
    router.post('/send-request', {
        'id' : id
    }, {
        onSuccess: () => {}
    })

}

const remove = (id) => {
  router.post('/remove-friendship', {
    'id' : id
  })
}

const refuse = (id) => {
    router.get('/refuse-request', {
        'id' : id
    })
}

const accept = (id) => {
    router.post('/accept-request', {
        'id' : id
    })
}

</script>

<style scoped>
.page-container {
  min-height: 100vh;
  background-color: #E6F2EF;
  padding: 5.5rem 2rem 2rem 2rem;
}

.page-title {
  color: #2E2E2E;
  font-size: 2.5rem;
  font-weight: 600;
  text-align: center;
  margin-bottom: 2rem;
}

.search-section {
  max-width: 600px;
  margin: 0 auto 3rem auto;
  position: relative;
}

.search-input {
  width: 100%;
  padding: 1rem 1.25rem;
  border: 2px solid #3FA796;
  border-radius: 12px;
  font-size: 1rem;
  color: #2E2E2E;
  background-color: white;
  transition: box-shadow 0.3s ease;
}

.search-input:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(63, 167, 150, 0.2);
}

.search-input::placeholder {
  color: #999;
}

.search-results {
  position: absolute;
  top: calc(100% + 0.5rem);
  left: 0;
  right: 0;
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  max-height: 400px;
  overflow-y: auto;
  z-index: 100;
}

.search-result-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #E6F2EF;
  transition: background-color 0.2s ease;
}

.search-result-item:last-child {
  border-bottom: none;
}

.search-result-item:hover {
  background-color: #f8fcfb;
}

.search-no-results {
  padding: 1.5rem;
  text-align: center;
  color: #666;
}

.user-name {
  color: #2E2E2E;
  font-weight: 500;
}

.tag {
  padding: 0.375rem 0.75rem;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 600;
}

.tag-friend {
  background-color: #d4edda;
  color: #155724;
}

.tag-pending {
  background-color: #fff3cd;
  color: #856404;
}

.add-button {
  background-color: #3FA796;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.add-button:hover {
  background-color: #35917f;
}

.friends-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

.card {
  background-color: white;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.card-title {
  color: #2E2E2E;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 3px solid #3FA796;
}

.user-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.user-item {
  background-color: #f8fcfb;
  padding: 1rem 1.25rem;
  border-radius: 8px;
  border: 2px solid #E6F2EF;
  transition: transform 0.2s ease;
}

.user-item:hover {
  transform: translateX(4px);
}

.user-item-actions {
  background-color: #f8fcfb;
  padding: 1rem 1.25rem;
  border-radius: 8px;
  border: 2px solid #E6F2EF;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.accept-button,
.refuse-button {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.1s ease;
}

.accept-button {
  background-color: #3FA796;
  color: white;
}

.accept-button:hover {
  background-color: #35917f;
}

.refuse-button {
  background-color: #e74c3c;
  color: white;
}

.refuse-button:hover {
  background-color: #c0392b;
}

.accept-button:active,
.refuse-button:active {
  transform: scale(0.97);
}

.empty-message {
  text-align: center;
  color: #999;
  padding: 2rem;
  font-style: italic;
}

@media (max-width: 768px) {
  .page-container {
    padding: 5.5rem 1rem 1rem 1rem;
  }
  
  .friends-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .page-title {
    font-size: 2rem;
  }
  
  .card {
    padding: 1.5rem;
  }
  
  .user-item-actions {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .action-buttons {
    width: 100%;
    flex-direction: column;
  }
  
  .accept-button,
  .refuse-button {
    width: 100%;
  }
}
</style>