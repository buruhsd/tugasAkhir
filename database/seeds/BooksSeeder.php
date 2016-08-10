<?php

use Illuminate\Database\Seeder;
use App\Book;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $book1 = Book::create(['title'=>'Belajar Laravel']);
        $book2 = Book::create(['title'=>'Panduan Html 5']);
        $book3 = Book::create(['title'=>'Belajar Jquery']);
    }
}
