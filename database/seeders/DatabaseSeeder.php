<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Review;
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
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        collect([
            ['code' => 'WELCOME10', 'type' => 'percent', 'value' => 10, 'max_uses' => null, 'expires_at' => null],
            ['code' => 'LUMA500', 'type' => 'fixed', 'value' => 500, 'max_uses' => 100, 'expires_at' => now()->addMonths(3)],
        ])->each(fn (array $coupon): Coupon => Coupon::updateOrCreate(
            ['code' => $coupon['code']],
            [...$coupon, 'is_active' => true],
        ));

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
                'images' => [
                    'https://images.unsplash.com/photo-1574258495973-f010dfbb5371?auto=format&fit=crop&w=900&q=85',
                    'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=900&q=85',
                    'https://images.unsplash.com/photo-1556306510-31ca015374b0?auto=format&fit=crop&w=900&q=85',
                ],
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
                'images' => [
                    'https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&w=900&q=85',
                    'https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&w=900&q=85',
                    'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=900&q=85',
                ],
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
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Ink Horizon Wayfarer',
                'slug' => 'ink-horizon-wayfarer',
                'description' => 'The classic wayfarer silhouette in deep matte black, cut for everyday wear from morning commute to evening light.',
                'image_url' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=900&q=85',
                'price' => 9800.00,
                'compare_at_price' => 11500.00,
                'stock' => 40,
                'sizes' => ['Medium', 'Wide'],
                'colors' => ['Jet Black', 'Matte Black'],
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Slate Angler',
                'slug' => 'slate-angler',
                'description' => 'A squared sport silhouette with a wraparound bridge and deep polarised lenses for full-day glare control.',
                'image_url' => 'https://images.unsplash.com/photo-1610136649349-0f646f318053?auto=format&fit=crop&w=900&q=85',
                'price' => 10200.00,
                'compare_at_price' => null,
                'stock' => 27,
                'sizes' => ['Wide'],
                'colors' => ['Slate Black'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Rosewater Round',
                'slug' => 'rosewater-round',
                'description' => 'Soft round metal lenses with a warm rose-toned finish for a delicate, retro-leaning look.',
                'image_url' => 'https://images.unsplash.com/photo-1618677366787-9727aacca7ea?auto=format&fit=crop&w=900&q=85',
                'price' => 9400.00,
                'compare_at_price' => null,
                'stock' => 3,
                'sizes' => ['Narrow', 'Medium'],
                'colors' => ['Rose Silver'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Golden Hour Aviator',
                'slug' => 'golden-hour-aviator',
                'description' => 'Brushed gold aviator frames with gradient lenses built for late afternoon light and long drives.',
                'image_url' => 'https://images.unsplash.com/photo-1567473810954-507d59716c25?auto=format&fit=crop&w=900&q=85',
                'price' => 11200.00,
                'compare_at_price' => 13400.00,
                'stock' => 22,
                'sizes' => ['Medium', 'Wide'],
                'colors' => ['Brushed Gold'],
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Amber Cat-Eye',
                'slug' => 'amber-cat-eye',
                'description' => 'A sculpted cat-eye shape in tortoise acetate with a lifted outer edge and honeyed lens tint.',
                'image_url' => 'https://images.unsplash.com/photo-1559070081-648fb00b2ed1?auto=format&fit=crop&w=900&q=85',
                'price' => 10600.00,
                'compare_at_price' => null,
                'stock' => 18,
                'sizes' => ['Medium'],
                'colors' => ['Amber Tortoise', 'Honey Fade'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Harbor Tortoise Round',
                'slug' => 'harbor-tortoise-round',
                'description' => 'A rounded tortoise frame with a slim keyhole bridge, made for quiet contrast against most skin tones.',
                'image_url' => 'https://images.unsplash.com/photo-1587310311582-aa7610e90826?auto=format&fit=crop&w=900&q=85',
                'price' => 9900.00,
                'compare_at_price' => null,
                'stock' => 0,
                'sizes' => ['Medium', 'Wide'],
                'colors' => ['Harbor Tortoise'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Club Row Clubmaster',
                'slug' => 'club-row-clubmaster',
                'description' => 'A browline clubmaster shape pairing a bold acetate top with a slim gold-tone metal base.',
                'image_url' => 'https://images.unsplash.com/photo-1502767089025-6572583495f9?auto=format&fit=crop&w=900&q=85',
                'price' => 10800.00,
                'compare_at_price' => 12900.00,
                'stock' => 15,
                'sizes' => ['Medium'],
                'colors' => ['Black Gold'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['sunglasses']->id,
                'name' => 'Cobalt Flight Aviator',
                'slug' => 'cobalt-flight-aviator',
                'description' => 'A gunmetal aviator with saturated cobalt lenses for a sharper, cooler-toned take on the classic shape.',
                'image_url' => 'https://images.unsplash.com/photo-1599705709640-9f9eb5964485?auto=format&fit=crop&w=900&q=85',
                'price' => 11900.00,
                'compare_at_price' => null,
                'stock' => 4,
                'sizes' => ['Wide'],
                'colors' => ['Gunmetal Blue'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['optical-frames']->id,
                'name' => 'Copper Wire Round',
                'slug' => 'copper-wire-round',
                'description' => 'A featherlight round wire frame with a warm copper finish and a barely-there double bridge.',
                'image_url' => 'https://images.unsplash.com/photo-1614715838608-dd527c46231d?auto=format&fit=crop&w=900&q=85',
                'price' => 9600.00,
                'compare_at_price' => null,
                'stock' => 25,
                'sizes' => ['Narrow', 'Medium'],
                'colors' => ['Copper Wire', 'Silver Wire'],
                'is_featured' => true,
            ],
            [
                'category_id' => $categories['optical-frames']->id,
                'name' => 'Boardroom Rectangle',
                'slug' => 'boardroom-rectangle',
                'description' => 'A sharp rectangular acetate frame with a matte finish, built for long reading sessions and video calls alike.',
                'image_url' => 'https://images.unsplash.com/photo-1556306510-31ca015374b0?auto=format&fit=crop&w=900&q=85',
                'price' => 8600.00,
                'compare_at_price' => 10200.00,
                'stock' => 30,
                'sizes' => ['Medium', 'Wide'],
                'colors' => ['Ink Black'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['optical-frames']->id,
                'name' => 'Fade Round',
                'slug' => 'fade-round',
                'description' => 'A round acetate frame that fades from smoke to clear at the base, with a soft matte texture.',
                'image_url' => 'https://images.unsplash.com/photo-1483412468200-72182dbbc544?auto=format&fit=crop&w=900&q=85',
                'price' => 9100.00,
                'compare_at_price' => null,
                'stock' => 12,
                'sizes' => ['Narrow', 'Medium'],
                'colors' => ['Smoke Fade'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['blue-light']->id,
                'name' => 'Night Owl Tortoise',
                'slug' => 'night-owl-tortoise',
                'description' => 'A rounded tortoise blue-light frame sized for long editing sessions after dark, with a soft matte coating.',
                'image_url' => 'https://images.unsplash.com/photo-1603578119639-798b8413d8d7?auto=format&fit=crop&w=900&q=85',
                'price' => 8900.00,
                'compare_at_price' => null,
                'stock' => 20,
                'sizes' => ['Medium'],
                'colors' => ['Night Tortoise'],
                'is_featured' => false,
            ],
            [
                'category_id' => $categories['blue-light']->id,
                'name' => 'Halo Rimless',
                'slug' => 'halo-rimless',
                'description' => 'A rimless cat-eye shape in warm gold wire, built to disappear on the face while still filtering screen glare.',
                'image_url' => 'https://images.unsplash.com/photo-1646084081219-1090f72a531c?auto=format&fit=crop&w=900&q=85',
                'price' => 9300.00,
                'compare_at_price' => 10900.00,
                'stock' => 2,
                'sizes' => ['Narrow', 'Medium'],
                'colors' => ['Halo Gold'],
                'is_featured' => true,
            ],
        ])->each(fn (array $product): Product => Product::updateOrCreate(
            ['slug' => $product['slug']],
            $product,
        ));

        collect([
            ['slug' => 'noir-keyhole', 'rating' => 5, 'title' => 'Exactly as pictured', 'body' => 'Lightweight and the fit is spot on. Ordered the medium and it sits perfectly.'],
            ['slug' => 'solar-round', 'rating' => 4, 'title' => 'Great for summer', 'body' => 'Love the tint, wish the case was a bit sturdier.'],
            ['slug' => 'studio-screen', 'rating' => 5, 'title' => null, 'body' => 'Barely notice I am wearing them during long work days.'],
        ])->each(function (array $review) use ($testUser): void {
            $product = Product::where('slug', $review['slug'])->first();

            if ($product === null) {
                return;
            }

            Review::updateOrCreate(
                ['user_id' => $testUser->id, 'product_id' => $product->id],
                ['rating' => $review['rating'], 'title' => $review['title'], 'body' => $review['body']],
            );
        });
    }
}
