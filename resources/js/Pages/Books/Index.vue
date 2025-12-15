<script setup>
import { ref, defineProps } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Header from '../components/header.vue';

const page = usePage();

const props = defineProps({
    books: Array,
    userId: Number
});

const showModal = ref(false);
const selectedBookId = ref(null);

const openModal = (bookId) => {
    selectedBookId.value = bookId;
    showModal.value = true;
};

const rentBook = (days) => {
    const userId = props.userId;
    if (!userId) {
        alert('Usuário não autenticado');
        return;
    }
    router.post('/rent-a-book', {
        book_id: selectedBookId.value,
        user_id: userId,
        rental_days: days
    });
    showModal.value = false;
    selectedBookId.value = null;
};

</script>

<template>

    <Header
    />

<div style="margin-top: 4.5rem;">
        <h1 class="">Lista de Livros</h1>
        

        <table>
            <thead>
                <tr class="">
                    <th class="">ID</th>
                    <th class="">Título</th>
                    <th class="">Autor</th>
                    <th class="">Total</th>
                    <th class="">Alugados</th>
                    <th class="">Disponíveis</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody >
                <tr v-for="book in props.books" :key="book.id" class="table-row">
                    <td class="">{{ book.id }}</td>
                    <td class="">{{ book.title }}</td>
                    <td class="">{{ book.writer_name }}</td>
                    <td class="">{{ book.total_copies }}</td>
                    <td class="">{{ book.rented_copies }}</td>
                    <td class="">{{ book.available_copies }}</td>
                    <td>
                        <span v-if="book.is_rented">Alugado</span>


                        <button 
                            v-else-if="book.already_rented" 
                            @click="openModal(book.id)"
                        >
                            Alugar novamente
                        </button>
                        
                        <button 
                            v-else-if="book.available_copies > 0" 
                            @click="openModal(book.id)"
                        >
                            Alugar
                        </button>
                        <span v-else>Indisponível</span>

        <div v-if="showModal" class="modal-overlay">
            <div class="modal-content">
                <h2>Por quanto tempo deseja alugar?</h2>
                <button @click="rentBook(7)">7 dias</button>
                <button @click="rentBook(30)">30 dias</button>
                <button @click="rentBook(60)">60 dias</button>
                <button @click="showModal = false">Cancelar</button>
            </div>
        </div>

                    </td>
                </tr>
            </tbody>
        </table>

        
    </div>
</template>

<style lang="css">
table {
    border: 2px solid black;
    border-collapse: collapse;
    width: 100%;
}

th, td {
    border: 2px solid black;
    padding: 0.5rem;
    text-align: left;
}

th {
    background-color: rgb(172, 172, 172);
}
.modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex; align-items: center; justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    text-align: center;
}
.modal-content button {
    margin: 0.5rem;
    padding: 0.5rem 1.5rem;
}
</style>