<template>
    <Header></Header>

    <div class="page-container">
        <div class="user-info">
            <h1 class="user-greeting">Olá, {{ props.User.name }}</h1>
        </div>

        <div class="info-container">
            <div class="rentals-section card">
                <h2 class="section-title">Seus Aluguéis</h2>
                
                <div class="rentals-grid">
                    <div v-for="rental in props.Rentals" :key="rental.id">
                        <div v-if="rental.returned_at == null" class="rental-card">    
                            <h3 class="book-title">{{ rental.book.title }}</h3>
                            <p class="rental-date">Data do aluguel: {{ rental.rented_at }}</p>
                            <p class="rental-due">Devolva ou renove em até {{ daysLeft(rental) }} dias</p>
                            <button @click="bookreturn(rental.id, rental.book)" class="return-button">Devolver</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grades-section card">
                <h2 class="section-title">Suas Avaliações</h2>

                <div class="grades-grid">
                    <div v-for="rental in uniqueRatedBooks" :key="rental.book.id" class="grade-card">
                        <span class="grade-book-title">{{ rental.book.title }}</span>
                        <span class="grade-rating">{{ '⭐'.repeat(rental.book.userRate) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pop-up de avaliação -->
        <div v-if="showRatingModal" class="modal-overlay" @click="closeModal">
            <div class="modal-content" @click.stop>
                <h2 v-if="!selectedBook.isRated" class="modal-title">Deseja avaliar "{{ selectedBook.title }}"?</h2>
                <h2 v-if="selectedBook.isRated" class="modal-title">Deseja mudar sua nota para "{{ selectedBook.title }}"?</h2>
                
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
    </div>
</template>
<script setup>
    import { ref } from 'vue';
    import { router, usePage } from '@inertiajs/vue3';
    import Header from '../components/header.vue';
    import { computed } from 'vue';

    const uniqueRatedBooks = computed(() => {
        const seen = new Set();
        return props.Rentals
            .filter(rental => rental.book.isRated && rental.returned_at)
            .filter(rental => {
                if (seen.has(rental.book.id)) return false;
                seen.add(rental.book.id);
                return true;
            });
    });

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

    function daysLeft(rental) {
        if (!rental.due_date) return '-';
        const dueDate = new Date(rental.due_date);
        const today = new Date();
        dueDate.setHours(0,0,0,0);
        today.setHours(0,0,0,0);
        const diffTime = dueDate - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        return diffDays >= 0 ? diffDays : 0;
    }

    const submitRating = () => {
        console.log(selectedBook.value)
        if (selectedRating.value === 0) {
            alert('Por favor, selecione uma nota!');
            return;
        }

        if (!selectedBook.value.isRated) {
            router.post('/rate-book', {
                book_id: selectedBook.value.id,
                rate: selectedRating.value
            }, {
                onSuccess: () => {
                    closeModal();
                }
            });
        } else {
            router.post('/change-rating', {
                book_id: selectedBook.value.id,
                rate: selectedRating.value
            }, {
                onSuccess: () => {
                    closeModal();
                }
            });
        }
    }

    const closeModal = () => {
        showRatingModal.value = false;
        selectedBook.value = null;
        selectedRating.value = 0;
    }
</script>

<style scoped>
.page-container {
  min-height: 100vh;
  background-color: #E6F2EF;
  padding: 5.5rem 2rem 2rem 2rem;
}

.user-info {
  text-align: center;
  margin-bottom: 2.5rem;
}

.user-greeting {
  color: #2E2E2E;
  font-size: 2.5rem;
  font-weight: 600;
}

.info-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

.card {
  background-color: white;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.section-title {
  color: #2E2E2E;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  padding-bottom: 0.75rem;
  border-bottom: 3px solid #3FA796;
}

.rentals-grid,
.grades-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.rental-card {
  background-color: #f8fcfb;
  border: 2px solid #E6F2EF;
  border-radius: 8px;
  padding: 1.5rem;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.rental-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.book-title {
  color: #2E2E2E;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.rental-date,
.rental-due {
  color: #555;
  font-size: 0.9rem;
  margin: 0.5rem 0;
}

.return-button {
  background-color: #3FA796;
  color: white;
  padding: 0.625rem 1.25rem;
  border: none;
  border-radius: 6px;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  margin-top: 1rem;
  width: 100%;
  transition: background-color 0.3s ease, transform 0.1s ease;
}

.return-button:hover {
  background-color: #35917f;
}

.return-button:active {
  transform: scale(0.98);
}

.grade-card {
  background-color: #f8fcfb;
  border: 2px solid #E6F2EF;
  border-radius: 8px;
  padding: 1rem 1.25rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  transition: transform 0.2s ease;
}

.grade-card:hover {
  transform: translateX(4px);
}

.grade-book-title {
  color: #2E2E2E;
  font-weight: 500;
}

.grade-rating {
  font-size: 1.1rem;
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
  max-width: 500px;
  width: 90%;
  text-align: center;
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
  margin-bottom: 1.5rem;
}

.rating-stars {
  margin: 1.5rem 0;
  font-size: 3rem;
}

.star {
  cursor: pointer;
  color: #ddd;
  transition: color 0.2s;
  margin: 0 0.1rem;
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

.btn-submit,
.btn-cancel {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 500;
  transition: background-color 0.3s ease, transform 0.1s ease;
}

.btn-submit {
  background-color: #3FA796;
  color: white;
}

.btn-submit:hover {
  background-color: #35917f;
}

.btn-cancel {
  background-color: #e3e3e3;
  color: #2E2E2E;
}

.btn-cancel:hover {
  background-color: #d0d0d0;
}

.btn-submit:active,
.btn-cancel:active {
  transform: scale(0.98);
}

@media (max-width: 768px) {
  .page-container {
    padding: 5.5rem 1rem 1rem 1rem;
  }
  
  .info-container {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .user-greeting {
    font-size: 2rem;
  }
  
  .card {
    padding: 1.5rem;
  }
}
</style>