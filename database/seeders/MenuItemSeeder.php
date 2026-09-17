<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = json_decode(
            file_get_contents(__DIR__ . '/data/menu-items.json'),
            true
        );

        $categoryIds = Category::query()->pluck('id', 'key');

        foreach ($items as $item) {
            $categoryId = $categoryIds[$item['category_key']] ?? null;

            if (! $categoryId) {
                continue;
            }

            MenuItem::updateOrCreate(
                ['item_key' => $item['item_key']],
                [
                    'category_id' => $categoryId,
                    'name' => $item['name'],
                    'name_ar' => $item['name_ar'],
                    'price' => $item['price'],
                    'badge' => $item['badge'],
                    'emoji' => $item['emoji'],
                    'description' => $item['description'],
                    'image_path' => $item['image_path'],
                    'sort_order' => $item['sort_order'],
                ]
            );
        }
    }
}
