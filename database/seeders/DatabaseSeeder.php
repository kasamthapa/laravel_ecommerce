<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = collect([
            [
                'name' => 'Optical Frames',
                'slug' => 'optical-frames',
                'description' => 'Prescription-ready silhouettes for everyday clarity and personal style.',
            ],
            [
                'name' => 'Sunglasses',
                'slug' => 'sunglasses',
                'description' => 'Tinted lenses and confident shapes for bright days and travel bags.',
            ],
            [
                'name' => 'Blue Light',
                'slug' => 'blue-light',
                'description' => 'Screen-focused frames with easy weight, calmer glare, and all-day comfort.',
            ],
        ])->mapWithKeys(fn (array $category): array => [
            $category['slug'] => Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category,
            ),
        ]);

        collect([
            [
                'category_id' => $categories['optical-frames']->id,
                'name' => 'Noir Keyhole',
                'slug' => 'noir-keyhole',
                'description' => 'A confident black acetate frame with a softened keyhole bridge and clean rectangular lens shape.',
                'image_url' => 'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&w=900&q=85',
                'price' => 11800.00,
                'compare_at_price' => 14800.00,
                'stock' => 32,
                'sizes' => ['Narrow', 'Medium', 'Wide'],
                'colors' => ['Ink Black', 'Smoke Grey'],
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['optical-frames']->id,
                'name' => 'Tortoise Atelier',
                'slug' => 'tortoise-atelier',
                'description' => 'A warm tortoise frame with slim temples, rounded corners, and a studio-polished finish.',
                'image_url' => 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=900&q=85',
                'price' => 13200.00,
                'compare_at_price' => null,
                'stock' => 24,
                'sizes' => ['Medium', 'Wide'],
                'colors' => ['Amber Tortoise', 'Dark Honey'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Solar Round',
                'slug' => 'solar-round',
                'description' => 'Round sun frames with a smoky lens, polished metal bridge, and vintage holiday energy.',
                'image_url' => 'https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&w=900&q=85',
                'price' => 9600.00,
                'compare_at_price' => 11900.00,
                'stock' => 46,
                'sizes' => ['Medium', 'Wide'],
                'colors' => ['Brushed Gold', 'Graphite'],
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Cobalt Shield',
                'slug' => 'cobalt-shield',
                'description' => 'A sculpted square sunglass with deep lenses and a bolder profile for full sun.',
                'image_url' => 'https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&w=900&q=85',
                'price' => 10400.00,
                'compare_at_price' => null,
                'stock' => 38,
                'sizes' => ['Wide'],
                'colors' => ['Cobalt', 'Black'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['blue-light']->id,
                'name' => 'Studio Screen',
                'slug' => 'studio-screen',
                'description' => 'Lightweight blue-light frames with slim lines and a barely-there feel for long laptop days.',
                'image_url' => 'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?auto=format&fit=crop&w=900&q=85',
                'price' => 8800.00,
                'compare_at_price' => 10800.00,
                'stock' => 19,
                'sizes' => ['Narrow', 'Medium'],
                'colors' => ['Clear Crystal', 'Matte Navy'],
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['blue-light']->id,
                'name' => 'Focus Wire',
                'slug' => 'focus-wire',
                'description' => 'A featherweight wire frame with soft nose pads, subtle blue-light filtering, and quiet detail.',
                'image_url' => 'https://images.unsplash.com/photo-1582142407894-ec85a1260a46?auto=format&fit=crop&w=900&q=85',
                'price' => 9200.00,
                'compare_at_price' => null,
                'stock' => 21,
                'sizes' => ['Medium'],
                'colors' => ['Silver', 'Rose Gold'],
                'is_featured' => false,
            ],
        ])->each(fn (array $product): Product => Product::updateOrCreate(
            ['slug' => $product['slug']],
            $product,
        ));
    }
}
