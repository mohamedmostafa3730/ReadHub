<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Role;
use App\Models\User;
use Database\Factories\OrderItemFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 2. Users
        $users = User::factory(10)->create();

        // 3. Categories
        $categories = Category::factory(5)->create();

        // 4. Books
        $books = Book::factory(20)->create();

        // 5. Authors
        $authors = Author::factory(10)->create();

        foreach ($books as $book) {
            $book->authors()->attach(
                $authors->random(rand(1, 3))->pluck('id')
            );
        }

        // 6. Orders + OrderItems
        foreach ($users as $user) {

            $orders = Order::factory(rand(1, 3))->create([
                'user_id' => $user->id
            ]);

            foreach ($orders as $order) {

                $total = 0;

                // ✅ FIXED LINE
                $items = Order_item::factory(rand(1, 5))->make();

                foreach ($items as $item) {

                    $book = $books->random();

                    $item->book_id = $book->id;
                    $item->order_id = $order->id;
                    $item->item_price = $book->price;

                    $item->save();

                    $total += $item->quantity * $item->item_price;
                }

                $order->update([
                    'total_price' => $total
                ]);
            }
        }
    }
}