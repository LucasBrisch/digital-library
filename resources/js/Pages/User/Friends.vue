<template>
    <Header />

<div style="margin-top: 4.5rem;">    
</div>

    <div class="container">

        <div class="search">
            <input type="text" class="input" placeholder="Encontrar pessoas" v-model="searchQuery">

            <div v-if="searchQuery" class="search-results">
                <div v-if="searchedUsers.length > 0">
                    <div v-for="user in searchedUsers" :key="user.id" class="search-result-item">
                        <span>{{ user.name }}</span>
                        <!-- TODO: add and 'add friend' button or an 'friend' / 'pending' tag if they have already interacted-->
                    </div>
                </div>
                <div v-else class="search-no-results">
                    Nenhum usuário encontrado.
                </div>
            </div>
        </div>


        <div class="friends">
            <h3>Amigos</h3>
            <div v-for=" user in props.users">
                <div v-if="user.status === 'friend'">{{ user.name }}</div>
            </div>
        </div>
------
        <div class="pending">
            <h3>Pedidos Pendentes</h3>
            <div v-for=" user in props.users">
                <div v-if="user.status === 'pending'">{{ user.name }}</div>
            </div>
        </div>
        
    </div>

</template>

<script setup>
import { ref, computed } from 'vue';
import Header from '../components/header.vue';

const props = defineProps ({
    users: Array,
    allusers : Array
})

const searchQuery = ref('');

const searchedUsers = computed(() => {
  if (!searchQuery.value) {
    return []; 
  }
  return props.allusers.filter(user =>
    user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

</script>
