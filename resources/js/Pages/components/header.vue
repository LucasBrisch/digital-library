<template>
<div class="main">
    <div class="left">
        <button @click="home" class="home">Home</button>
    </div>

    <div class="right">
        <button @click="friends" class="logout">Amigos</button>
        <button @click="profile" class="logout">Perfil</button>
        <button @click="logout" class="logout">Sair</button>
        <div style="margin-right: 1rem;"></div> <!-- I know, but I don't care, it works -->
    </div>
</div>

</template>

<style lang="css" scoped>

.main {
    background-color: #1E3A34;
    margin: 0;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 1rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.left, .right {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.home, .logout {
    color: #FFFFFF;
    font-size: 1rem;
    font-weight: 500;
    background-color: transparent;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.2s ease-in-out;
}

.home:hover, .logout:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

@media (max-width: 480px) {
    .main {
        padding: 0 0.5rem;
    }
    .home, .logout {
        font-size: 0.8rem;
        padding: 0.4rem 0.6rem;
    }
    .right {
        gap: 0.2rem;
    }
}

</style>

<script setup>
import { router } from '@inertiajs/vue3';
const logout = () => {
    router.post('/logout');
};
const profile = () => {
    router.get('profile')
}
const home = () => {
    router.get('/books')
}
const friends = () => {
    router.get('/friends')
}
</script>