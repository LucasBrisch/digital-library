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

    <Header />

    <div class="page-container">
        <h1 class="page-title">Lista de Livros</h1>
        
        <div class="table-wrapper">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Total</th>
                        <th>Alugados</th>
                        <th>Disponíveis</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="book in props.books" :key="book.id" class="table-row">
                        <td>{{ book.id }}</td>
                        <td class="book-title">{{ book.title }}</td>
                        <td>{{ book.writer_name }}</td>
                        <td>{{ book.total_copies }}</td>
                        <td>{{ book.rented_copies }}</td>
                        <td>{{ book.available_copies }}</td>
                        <td>
                            <span v-if="book.is_rented" class="status-badge status-rented">Alugado</span>

                            <button 
                                v-else-if="book.already_rented" 
                                @click="openModal(book.id)"
                                class="action-button"
                            >
                                Alugar novamente
                            </button>
                            
                            <button 
                                v-else-if="book.available_copies > 0" 
                                @click="openModal(book.id)"
                                class="action-button"
                            >
                                Alugar
                            </button>
                            <span v-else class="status-badge status-unavailable">Indisponível</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showModal" class="modal-overlay" @click="showModal = false">
            <div class="modal-content" @click.stop>
                <h2 class="modal-title">Por quanto tempo deseja alugar?</h2>
                <div class="modal-buttons">
                    <button @click="rentBook(7)" class="modal-button">7 dias</button>
                    <button @click="rentBook(30)" class="modal-button">30 dias</button>
                    <button @click="rentBook(60)" class="modal-button">60 dias</button>
                    <button @click="showModal = false" class="modal-button cancel">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.page-container {
  min-height: 100vh;
  background-color: #E6F2EF;
  padding: 5.5rem 2rem 2rem 2rem;
}

.page-title {
  color: #2E2E2E;
  font-size: 2rem;
  font-weight: 600;
  margin-bottom: 2rem;
  text-align: center;
}

.table-wrapper {
  background-color: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  max-width: 1400px;
  margin: 0 auto;
}

.books-table {
  width: 100%;
  border-collapse: collapse;
}

.books-table thead {
  background-color: #3FA796;
}

.books-table th {
  color: white;
  padding: 1rem;
  text-align: left;
  font-weight: 600;
  font-size: 0.95rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.books-table td {
  padding: 1rem;
  color: #2E2E2E;
  border-bottom: 1px solid #E6F2EF;
}

.table-row:hover {
  background-color: #f8fcfb;
}

.table-row:last-child td {
  border-bottom: none;
}

.book-title {
  font-weight: 500;
}

.status-badge {
  display: inline-block;
  padding: 0.375rem 0.75rem;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 600;
}

.status-rented {
  background-color: #d1ecf1;
  color: #0c5460;
}

.status-unavailable {
  background-color: #f8d7da;
  color: #721c24;
}

.action-button {
  background-color: #3FA796;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  font-size: 0.9rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.1s ease;
}

.action-button:hover {
  background-color: #35917f;
}

.action-button:active {
  transform: scale(0.97);
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.modal-content {
  background-color: white;
  padding: 2.5rem;
  border-radius: 12px;
  text-align: center;
  max-width: 500px;
  width: 90%;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
  animation: slideUp 0.3s ease;
}

@keyframes slideUp {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-title {
  color: #2E2E2E;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 2rem;
}

.modal-buttons {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.modal-button {
  background-color: #3FA796;
  color: white;
  padding: 0.875rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.1s ease;
}

.modal-button:hover {
  background-color: #35917f;
}

.modal-button:active {
  transform: scale(0.98);
}

.modal-button.cancel {
  background-color: #e0e0e0;
  color: #2E2E2E;
}

.modal-button.cancel:hover {
  background-color: #d0d0d0;
}

@media (max-width: 768px) {
  .page-container {
    padding: 5.5rem 1rem 1rem 1rem;
  }
  
  .books-table {
    font-size: 0.85rem;
  }
  
  .books-table th,
  .books-table td {
    padding: 0.75rem 0.5rem;
  }
  
  .page-title {
    font-size: 1.5rem;
  }
}
</style>