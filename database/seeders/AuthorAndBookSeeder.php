<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;
use App\Models\Book;


class AuthorAndBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gerar 100 autores cada um com 10 livros
        Author::factory(100)->hasBooks(10)->create();

    }
}
