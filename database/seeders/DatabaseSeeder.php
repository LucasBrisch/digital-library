<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Rental;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Criar 5 usuários
        $users = User::factory()->count(5)->create();

        // Criar alguns livros
        $books = Book::insert([
            [
                'title' => 'O Senhor dos Anéis',
                'total_copies' => 10,
                'writer_name' => 'J. R. R. Tolkien',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => '1984',
                'total_copies' => 7,
                'writer_name' => 'George Orwell',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Dom Casmurro',
                'total_copies' => 5,
                'writer_name' => 'Machado de Assis',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Buscar os livros recém-criados
        $bookList = Book::all();

        // Criar alguns rentals
        foreach ($bookList as $book) {
            Rental::create([
                'book_id' => $book->id,
                'user_id' => $users->random()->id,
                'rented_at' => now()->subDays(rand(1, 10)),
                'returned_at' => rand(0,1) ? now()->subDays(rand(0, 5)) : null
            ]);
        }
    }
}
