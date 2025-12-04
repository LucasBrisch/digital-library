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
                    <div v-for="rental in props.Rentals">
                        <div v-if="rental.returned_at == null" class="rent-object">    
                            {{ rental.book.title }} 
                            <p>Data do aluguel: {{ rental.rented_at }}</p>
                            <button @click="bookreturn(rental.id, rental.book)">Devolver</button>
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


        <!-- Pop-up de avaliação -->
        <div v-if="showRatingModal" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <h2>Deseja avaliar "{{ selectedBook.title }}"?</h2>
                
                <div class="rating-stars">
                    <span v-for="star in 5" :key="star" 
                          @click="selectedRating = star"
                          :class="{ 'active': star <= selectedRating }"
                          class="star">
                        ★
                    </span>
                </div>
                
                <div class="modal-buttons">
                    <button @click="submitRating" class="btn-submit">Avaliar</button>
                    <button @click="closeModal" class="btn-cancel">Agora não</button>
                </div>
            </div>
        </div>

        <!-- <pre>{{ props.Rentals }}</pre> -->
    </div>


</template>
<script setup>
    import { ref } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
    import Header from '../components/header.vue';

    const props = defineProps ({
        Rentals : Array,
        User : Object,
    })

    const showRatingModal = ref(false);
    const selectedBook = ref(null);
    const selectedRating = ref(0);

    const bookreturn = (rentalId, book) => {
        router.post('/return-book', {
            "rental_id" : rentalId
        }, {
            onSuccess: () => {
                selectedBook.value = book;
                showRatingModal.value = true;
            }
        })
    }

    const submitRating = () => {
        if (selectedRating.value === 0) {
            alert('Por favor, selecione uma nota!');
            return;
        }
        alert(selectedRating.value)
        router.post('/rate-book', {
            book_id: selectedBook.value.id,
            rate: selectedRating.value
        }, {
            onSuccess: () => {
                closeModal();
            }
        });
    }

    const closeModal = () => {
        showRatingModal.value = false;
        selectedBook.value = null;
        selectedRating.value = 0;
    }
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

/* MODAL */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background-color: white;
    padding: 2rem;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    text-align: center;
}

.rating-stars {
    margin: 1.5rem 0;
    font-size: 3rem;
}

.star {
    cursor: pointer;
    color: #ddd;
    transition: color 0.2s;
}

.star.active {
    color: #ffd700;
}

.star:hover {
    color: #ffed4e;
}

.modal-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 1.5rem;
}

.btn-submit, .btn-cancel {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
}

.btn-submit {
    background-color: #3490dc;
    color: white;
}

.btn-cancel {
    background-color: #e3e3e3;
    color: #333;
}
</style>