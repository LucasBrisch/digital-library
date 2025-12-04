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
                    <div v-for="rent in props.Rentals">
                        <div v-if="rent.returned_at == null" class="rent-object">    
                            {{ rent.book.title }} 
                            <p>Data do aluguel: {{ rent.rented_at }}</p>
                            <button @click="bookreturn(rent)">Devolver</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grades-container">
                <h2> Suas avaliações</h2>

                <div class="grades">

                </div>
            </div>
            
        </div>

        <!-- <pre>{{ props.Rentals }}</pre> -->
    </div>


</template>
<script setup>
    import Header from '../components/header.vue';


    const props = defineProps ({
        Rentals : Array,
        User : Object,
    })

    const bookreturn = ($rent) => {}
</script>
<style>


.user-info {
    justify-content: center;
    display: flex;
}

/* GRADES */

.grades-container{
    border: 2px solid purple
}
.grades {
    border: 2px solid black;
}


/* RENT */

.rentals-container {
    border: 2px solid aqua;
}
.rentals {
    border: 2px solid blue;
}
.rent-object {
    display: flex;
    flex-direction: column;
}

.rent-object button {
    margin-bottom: 0.2rem;
}


.info-container {
    border: 2px solid pink;
    flex-direction: row;
    display: flex;
    justify-content: space-evenly;
}
</style>