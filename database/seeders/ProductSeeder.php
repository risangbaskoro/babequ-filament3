<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::factory(rand(5, 10))
            ->create();

        $users = User::where('role', UserRole::CITIZEN->value)
            ->get();

        $categories->each(function (Category $category) use ($users) {
            $users->each(function (User $user) use ($category) {
                Product::factory(rand(1, 5))
                    ->create([
                        'category_id' => $category,
                        'user_id' => $user,
                    ]);
            });
        });
    }
}
