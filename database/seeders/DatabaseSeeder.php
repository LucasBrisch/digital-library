<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Friendship;
use App\Models\Rental;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Criar usuários específicos
        $users = collect([
            User::create([
                'name' => 'Lucas Brisch',
                'email' => 'lucas@biblioteca.com',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]),
            User::create([
                'name' => 'Maria Silva',
                'email' => 'maria@biblioteca.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]),
            User::create([
                'name' => 'João Santos',
                'email' => 'joao@biblioteca.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]),
            User::create([
                'name' => 'Ana Costa',
                'email' => 'ana@biblioteca.com',
                'password' => bcrypt('password'),
                'role' => 'librarian',
            ]),
            User::create([
                'name' => 'Pedro Oliveira',
                'email' => 'pedro@biblioteca.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]),
        ]);

        $lucas = $users->firstWhere('email', 'lucas@biblioteca.com');
        $maria = $users->firstWhere('email', 'maria@biblioteca.com');
        $joao = $users->firstWhere('email', 'joao@biblioteca.com');
        $ana = $users->firstWhere('email', 'ana@biblioteca.com');
        $pedro = $users->firstWhere('email', 'pedro@biblioteca.com');

        Friendship::create([
            'requester_id' => $lucas->id,
            'addressee_id' => $maria->id,
            'status' => 'accepted',
        ]);

        Friendship::create([
            'requester_id' => $lucas->id,
            'addressee_id' => $joao->id,
            'status' => 'pending',
        ]);

        Friendship::create([
            'requester_id' => $ana->id,
            'addressee_id' => $lucas->id,
            'status' => 'pending',
        ]);

        Friendship::create([
            'requester_id' => $lucas->id,
            'addressee_id' => $pedro->id,
            'status' => 'blocked',
        ]);

        // Criar alguns livros
        Book::insert([
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
        // $bookList = Book::all();

        // Criar alguns rentals
        // foreach ($bookList as $book) {
        //     Rental::create([
        //         'book_id' => $book->id,
        //         'user_id' => $users->random()->id,
        //         'rented_at' => now()->subDays(rand(1, 10)),
        //         'returned_at' => rand(0,1) ? now()->subDays(rand(0, 5)) : null
        //     ]);
        // }

        // Criar algumas avaliações

        // $ratings = [];
        // foreach ($bookList as $book) {
        //     // Cada livro recebe de 2 a 5 avaliações
        //     $numberOfRatings = rand(2, 5);
        //     $usedUsers = [];
            
        //     for ($i = 0; $i < $numberOfRatings; $i++) {
        //         // Garantir que o mesmo usuário não avalie o mesmo livro duas vezes
        //         do {
        //             $user = $users->random();
        //         } while (in_array($user->id, $usedUsers));
                
        //         $usedUsers[] = $user->id;
                
        //             $ratings[] = [
        //                 'book_id' => $book->id,
        //                 'user_id' => $user->id,
        //                 'rate' => rand(1, 5), // Notas de 1 a 5
        //             'created_at' => now(),
        //             'updated_at' => now()
        //         ];
        //     }
        // }

        // DB::table('rating')->insert($ratings);
    }
}