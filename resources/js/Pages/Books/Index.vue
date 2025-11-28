<script setup>
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    books: Array
});

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Lista de Livros</h1>
        <button @click="logout">Sair</button>

        <table class="min-w-full border">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Título</th>
                    <th class="p-2 border">Autor</th>
                    <th class="p-2 border">Total</th>
                    <th class="p-2 border">Alugados</th>
                    <th class="p-2 border">Disponíveis</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="book in props.books" :key="book.id" class="border-b">
                    <td class="p-2 border">{{ book.id }}</td>
                    <td class="p-2 border">{{ book.title }}</td>
                    <td class="p-2 border">{{ book.writer_name }}</td>
                    <td class="p-2 border">{{ book.total_copies }}</td>
                    <td class="p-2 border">{{ book.rented_copies }}</td>
                    <td class="p-2 border font-bold">{{ book.available_copies }}</td>
                </tr>
            </tbody>
        </table>

        
    </div>
</template>
