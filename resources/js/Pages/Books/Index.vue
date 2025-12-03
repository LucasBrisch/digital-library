<script setup>
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';
import Header from '../components/header.vue';

const props = defineProps({
    books: Array
});


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
                        <button>Alugar</button>
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