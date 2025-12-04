<template>

    <Header></Header>

    <div style="margin-top: 4.5rem;">
        <div class="user-info">
            <h1>Olá {{ props.User.name }}</h1>
        </div>

        <div class="info-container">

            <div class="rentals-container">
                <h2> Seus alugueis</h2>
                
                <div class="rentals">
                    
                </div>
            </div>
            
            <div class="grades-container">
                <h2> Suas avaliações</h2>

                <div class="grades">

                </div>
            </div>
            
        </div>
    </div>


</template>
<script setup>
    import Header from '../components/header.vue';


    const props = defineProps ({
        Rentals : Array,
        User : Object,
    })
</script>
<style>
.user-info {
    justify-content: center;
    display: flex;
}
.grades-container{
    border: 2px solid purple
}
.grades {
    border: 2px solid black;
}

.rentals-container {
    border: 2px solid aqua;
}
.rentals {
    border: 2px solid blue;
}

.info-container {
    border: 2px solid pink;
    flex-direction: row;
    display: flex;
    justify-content: space-evenly;
}
</style>