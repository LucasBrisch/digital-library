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
                         <span v-if="isFriend(user)" class="tag-friend"> Amigo</span>
                        <span v-else-if="isPending(user)" class="tag-pending"> Pendente</span>
                        <span v-else><button @click="addfriend(user.id)">adicionar amigo</button></span>
                    </div>
                </div>
                <div v-else class="search-no-results">
                    Nenhum usuário encontrado.
                </div>
            </div>
        </div>


        <div class="friends">
            <h3>Amigos</h3>
            <div v-for=" user in friends">
                <div>{{ user.name }}</div>
            </div>
        </div>

        <div class="pending">
            <h3>Pedidos Pendentes</h3>
            <div v-for=" user in pendings">
                <div>{{ user.name }}</div>
            </div>
        </div>

        <div class="received">
            <h3>Pedidos Recebidos</h3>
            <div v-for=" user in received">
                <div>{{ user.name }}</div>
                <button @click="accept(user.id)">Aceitar</button>
                <button @click="refuse(user.id)">Recusar</button>
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
