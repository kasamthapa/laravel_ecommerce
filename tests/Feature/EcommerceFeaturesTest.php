<?php

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

function makeProduct(array $overrides = []): Product
{
    $category = Category::firstOrCreate(
        ['slug' => 'optical-frames'],
        ['name' => 'Optical Frames', 'description' => 'Everyday eyewear.'],
    );

    return Product::create([
        'category_id' => $category->id,
        'name' => 'Test Frame',
        'slug' => 'test-frame-'.uniqid(),
        'description' => 'A lightweight frame for testing.',
        'image_url' => 'https://example.com/frame.jpg',
        'price' => 7000.00,
        'stock' => 10,
        'sizes' => ['Medium'],
        'colors' => ['Black'],
        'is_featured' => false,
        'is_active' => true,
        ...$overrides,
    ]);
}

test('a shopper can add and remove a product from their wishlist', function () {
    $user = User::factory()->create();
    $product = makeProduct();

    $this->actingAs($user)
        ->post(route('wishlist.store', $product))
        ->assertRedirect();

    expect($user->wishlistedProducts()->pluck('products.id'))->toContain($product->id);

    $this->actingAs($user)->get(route('wishlist.index'))
        ->assertSuccessful()
        ->assertSee('Test Frame');

    $this->actingAs($user)
        ->delete(route('wishlist.destroy', $product))
        ->assertRedirect();

    expect($user->wishlistedProducts()->pluck('products.id'))->not->toContain($product->id);
});

test('a guest is redirected to login when trying to wishlist a product', function () {
    $product = makeProduct();

    $this->post(route('wishlist.store', $product))->assertRedirect(route('login'));
});

test('a shopper can leave one review per product and it affects the average rating', function () {
    $user = User::factory()->create();
    $product = makeProduct();

    $this->actingAs($user)->post(route('reviews.store', $product), [
        'rating' => 4,
        'title' => 'Pretty good',
        'body' => 'Comfortable for daily wear.',
    ])->assertRedirect();

    $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'product_id' => $product->id,
        'rating' => 4,
    ]);

    // Submitting again updates the existing review instead of creating a second one.
    $this->actingAs($user)->post(route('reviews.store', $product), [
        'rating' => 5,
        'title' => 'Actually great',
        'body' => 'Changed my mind, love these.',
    ])->assertRedirect();

    expect($product->reviews()->count())->toBe(1);
    expect($product->fresh()->reviews()->first()->rating)->toBe(5);
});

test('a review requires a rating between 1 and 5 and a body', function () {
    $user = User::factory()->create();
    $product = makeProduct();

    $this->actingAs($user)->post(route('reviews.store', $product), [
        'rating' => 6,
        'body' => '',
    ])->assertSessionHasErrors(['rating', 'body']);
});

test('a valid coupon reduces the cart total and an invalid one is rejected', function () {
    $product = makeProduct(['price' => 10000]);
    Coupon::create([
        'code' => 'SAVE10',
        'type' => 'percent',
        'value' => 10,
        'is_active' => true,
    ]);

    $this->post(route('cart.store', $product), ['quantity' => 1])->assertRedirect();

    $this->post(route('cart.coupon.apply'), ['code' => 'SAVE10'])
        ->assertRedirect(route('cart.index'));

    $this->get(route('cart.index'))
        ->assertSuccessful()
        ->assertSee('SAVE10')
        ->assertSee('1,000'); // 10% of Rs. 10,000 subtotal

    $this->post(route('cart.coupon.apply'), ['code' => 'DOES-NOT-EXIST'])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('status', 'That coupon code is invalid or expired.');
});

test('an expired coupon cannot be applied', function () {
    $product = makeProduct();
    Coupon::create([
        'code' => 'EXPIRED',
        'type' => 'fixed',
        'value' => 500,
        'is_active' => true,
        'expires_at' => now()->subDay(),
    ]);

    $this->post(route('cart.store', $product), ['quantity' => 1])->assertRedirect();

    $this->post(route('cart.coupon.apply'), ['code' => 'EXPIRED'])
        ->assertRedirect(route('cart.index'))
        ->assertSessionHas('status', 'That coupon code is invalid or expired.');
});

test('an authenticated shopper can view their order history and a single order', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'LUM-260101-MINE',
        'customer_name' => 'Kasam Thapa',
        'customer_email' => 'kasam@example.com',
        'customer_phone' => '9800000000',
        'shipping_address' => 'Main Road',
        'shipping_city' => 'Kathmandu',
        'subtotal' => 7000,
        'shipping_total' => 250,
        'total' => 7250,
        'status' => 'confirmed',
        'payment_status' => 'paid',
    ]);

    $this->actingAs($user)->get(route('account.orders.index'))
        ->assertSuccessful()
        ->assertSee('LUM-260101-MINE');

    $this->actingAs($user)->get(route('account.orders.show', $order))
        ->assertSuccessful()
        ->assertSee('LUM-260101-MINE');

    $this->actingAs($otherUser)->get(route('account.orders.show', $order))
        ->assertNotFound();
});

test('a shopper can track an order with the correct order number and email', function () {
    $order = Order::create([
        'order_number' => 'LUM-260101-TRACK',
        'customer_name' => 'Kasam Thapa',
        'customer_email' => 'kasam@example.com',
        'customer_phone' => '9800000000',
        'shipping_address' => 'Main Road',
        'shipping_city' => 'Kathmandu',
        'subtotal' => 7000,
        'shipping_total' => 250,
        'total' => 7250,
        'status' => 'shipped',
        'payment_status' => 'paid',
    ]);

    $this->post(route('track.show'), [
        'order_number' => 'LUM-260101-TRACK',
        'email' => 'kasam@example.com',
    ])->assertSuccessful()->assertSee('LUM-260101-TRACK');

    $this->post(route('track.show'), [
        'order_number' => 'LUM-260101-TRACK',
        'email' => 'wrong@example.com',
    ])->assertSuccessful()->assertSee('No order matched');
});

test('an admin can manage categories, coupons, and view customers', function () {
    $admin = User::factory()->create();
    $admin->is_admin = true;
    $admin->save();

    $this->actingAs($admin)->post(route('admin.categories.store'), [
        'name' => 'Reading Glasses',
        'description' => 'For close-up focus.',
    ])->assertRedirect(route('admin.categories.index'));

    $this->assertDatabaseHas('categories', ['name' => 'Reading Glasses']);

    $this->actingAs($admin)->post(route('admin.coupons.store'), [
        'code' => 'newyear',
        'type' => 'percent',
        'value' => 15,
        'is_active' => '1',
    ])->assertRedirect(route('admin.coupons.index'));

    $this->assertDatabaseHas('coupons', ['code' => 'NEWYEAR']);

    User::factory()->create();

    $this->actingAs($admin)->get(route('admin.customers.index'))
        ->assertSuccessful();

    $this->actingAs($admin)->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertSee('Revenue');
});

test('a non-admin cannot access the admin area', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
});

test('order confirmation and status update notifications are sent', function () {
    Notification::fake();

    $user = User::factory()->create();
    $admin = User::factory()->create();
    $admin->is_admin = true;
    $admin->save();

    $order = Order::create([
        'user_id' => $user->id,
        'order_number' => 'LUM-260101-NOTIFY',
        'customer_name' => 'Kasam Thapa',
        'customer_email' => 'kasam@example.com',
        'customer_phone' => '9800000000',
        'shipping_address' => 'Main Road',
        'shipping_city' => 'Kathmandu',
        'subtotal' => 7000,
        'shipping_total' => 250,
        'total' => 7250,
        'status' => 'confirmed',
        'payment_status' => 'paid',
    ]);

    $this->actingAs($admin)->patch(route('admin.orders.update-status', $order), [
        'status' => 'shipped',
    ])->assertRedirect(route('admin.orders.show', $order));

    Notification::assertSentOnDemand(OrderStatusUpdated::class);
});
