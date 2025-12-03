<template>
<div class="main">

    <div class="left">
        a
    </div>

    <div class="right">

        <div class="logout-container">
            <button @click="profile" class="logout">Perfil</button>
        </div>

        <div class="logout-container">
            <button @click="logout" class="logout">Sair</button>
            <!-- <img src="" alt=""> -->
        </div>
    </div>
    
</div>

</template>

<style lang="css">

.main {
    background-color: aqua;
    margin: 0;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4.5rem;
    display: flex
    ;
}

.left {
    width: 50%;
    border: 2px purple solid;
}

.right {
    width: 50%;
    border: 2px green solid;
    display: flex;
    justify-content: flex-end;
}
.logout-container {
    flex-direction: column;
    justify-content: center;
    display: flex;
}
.logout {
    color: rgb(255, 255, 255);
    font-size:large;

    background-color: rgb(85, 84, 84);
    border: none;
    height: 45%;
    width: 4rem;
    margin-right: 0.5rem;
    border-radius: 12px;
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
</script>