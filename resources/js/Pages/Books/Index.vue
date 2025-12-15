<script setup>
import { defineProps } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import Header from '../components/header.vue';

const page = usePage();

const props = defineProps({
    books: Array,
    userId: Number
});

const rentBook = (bookId) => {
    const userId = props.userId;
    if (!userId) {
        alert('Usuário não autenticado');
        return;
    }

    router.post('/rent-a-book', {
        book_id: bookId,
        user_id: userId
    });
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
                        @click="rentBook(book.id)"
                        >
                        Alugar novamente
                        </button>
                        
                        <button 
                            v-else-if="book.available_copies > 0" 
                            @click="rentBook(book.id)"
                        >
                            Alugar
                        </button>

                        <span v-else>Indisponível</span>
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
</style>