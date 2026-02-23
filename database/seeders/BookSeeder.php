<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'This Arab Is Queer',
                'author' => 'Elias Jahshan',
                'description' => 'An anthology by LGBTQ+ Arab writers',
                'category_slug' => 'queer-autobiography',
            ],
            [
                'title' => 'Tengo Miedo Torero',
                'author' => 'Pedro Lemebel',
                'description' => 'Love story in 1986 Chile',
                'category_slug' => 'trans-studies',
            ],
            [
                'title' => 'Antología Queer',
                'author' => 'Ángelo Néstor',
                'description' => 'Queer poetry anthology',
                'category_slug' => 'queer-poetry',
            ],
            [
                'title' => 'Fable For the End of the World',
                'author' => 'Ava Reid',
                'description' => 'We all do what we have to do in order to survive...',
                'category_slug' => 'lesbian-fiction',
            ],
            [
                'title' => 'Cuerpos para odiar',
                'author' => 'Claudia Rodríguez',
                'description' => 'Retrato crudo, auténtico y conmovedor de la vida de las travestis',
                'category_slug' => 'queer-autobiography',
            ],
            [
                'title' => 'On earth we are briefly gorgeus',
                'author' => 'Ocean Vuong',
                'description' => 'A letter from a son to a mother who cannot read',
                'category_slug' => 'queer-autobiography',
            ],
            [
                'title' => 'Are you this? Or are you this?',
                'author' => 'Madian Al Jazerah',
                'description' => 'Frank and moving memoir narrating Madians battles with adversity, racism and homophobia',
                'category_slug' => 'queer-autobiography',
            ],
            [
                'title' => 'Lonely Receiver',
                'author' => 'Zac Thompson',
                'description' => 'A horror/breakup story in five parts',
                'category_slug' => 'lesbian-fiction',
            ],
            [
                'title' => 'Laudes',
                'author' => 'Xelsoi',
                'description' => 'Incómoda y provocativa que escupe en los relatos optimistas sobre el sexo, la imagen y el trabajo',
                'category_slug' => 'gay-fiction',
            ],
            [
                'title' => 'This is how you lose the time war',
                'author' => 'Amal el Mohtar',
                'description' => 'Among the ashes of a dying world, an agent on the Commandant finds a letter. It reads: Burn before reading',
                'category_slug' => 'lesbian-fiction',
            ],
            [
                'title' => 'A little life',
                'author' => 'Hanya Yanagihara',
                'description' => 'Four college classmates-broke, adrift, and buoyed only by their friendship and ambition',
                'category_slug' => 'gay-fiction',
            ],
            [
                'title' => 'Ella, no',
                'author' => 'Val Flores',
                'description' => '57 laconismos postapocalípticos - o la masacre de una lesbiana eremita',
                'category_slug' => 'queer-poetry',
            ],
        ];

        foreach ($books as $bookData) {
            $category = Category::where('slug', $bookData['category_slug'])->first();

            $book = Book::create([
                'title' => $bookData['title'],
                'author' => $bookData['author'],
                'description' => $bookData['description'],
            ]);

            $book->categories()->attach($category->id);
        }
    }
}