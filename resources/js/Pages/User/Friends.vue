
<template>
    <Header />

<div style="margin-top: 4.5rem;">    
</div>

    <div class="container">

        <form>
            <input type="text" class="input" placeholder="Encontrar pessoas">
            <button @click="findePeople">Buscar</button>
        </form>
        
    </div>
</template>

<script setup>
import Header from '../components/header.vue';

const findePeople = () => {
    router.get()
}

</script>