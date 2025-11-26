
<template>
    <h1>Lista de Livros</h1>

    <ul>
        <li v-for="book in books" :key="book.id">
            {{ book.title }} — {{ book.writer_name }}
        </li>
    </ul>
</template>

<script setup>
defineProps({
    books: Array
});
</script>