
<template>
    <Header />

<div style="margin-top: 4.5rem;">    
</div>

    <div class="container">

        <form>
            <input type="text" class="input" placeholder="Encontrar pessoas">
            <button @click="findePeople">Buscar</button>
        </form>

        <div class="friends">
            <div v-for=" user in props.users">
                <div v-if="user.status === 'friend'">{{ user.name }}</div>
            </div>
        </div>
------
        <div class="pending">
            <div v-for=" user in props.users">
                <div v-if="user.status === 'pending'">{{ user.name }}</div>
            </div>
        </div>
        
    </div>

</template>

<script setup>
import Header from '../components/header.vue';

const props = defineProps ({
    users: Array,
    allusers : Array
})

const findePeople = () => {
    router.get()
}

</script>