<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

test('the storefront shows seeded products', function () {
    $this->seed();

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('Luma Lens')
        ->assertSee('Noir Keyhole');
});

test('a shopper can search the product catalog', function () {
    $this->seed();

    $this->get(route('shop', ['q' => 'Solar']))
        ->assertSuccessful()
        ->assertSee('wire:model.live.debounce.400ms', false)
        ->assertSee('Solar Round')
        ->assertDontSee('Noir Keyhole');

    $this->get(route('shop', ['q' => 'not-a-frame']))
        ->assertSuccessful()
        ->assertSee('No frames found');
});

test('a guest can add to cart but must login before checkout', function () {
    $category = Category::create([
        'name' => 'Optical Frames',
        'slug' => 'optical-frames',
        'description' => 'Everyday eyewear.',
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Test Frame',
        'slug' => 'test-frame',
        'description' => 'A lightweight frame for testing.',
        'image_url' => 'https://example.com/frame.jpg',
        'price' => 7000.00,
        'stock' => 10,
        'sizes' => ['Medium', 'Wide'],
        'colors' => ['Black'],
        'is_featured' => true,
        'is_active' => true,
    ]);

    $this->get(route('products.show', $product))
        ->assertSuccessful()
        ->assertSee('Test Frame');

    $this->post(route('cart.store', $product), [
        'size' => 'Medium',
        'color' => 'Black',
        'quantity' => 2,
    ])->assertRedirect(route('cart.index'));

    $this->get(route('cart.index'))
        ->assertSuccessful()
        ->assertSee('Test Frame')
        ->assertSee('Rs. 14,000')
        ->assertSee('Login to checkout');

    $this->get(route('checkout.create'))->assertRedirect(route('login'));

    $order = Order::create([
        'order_number' => 'LUM-260715-TEST',
        'customer_name' => 'Kasam Thapa',
        'customer_email' => 'kasam@example.com',
        'customer_phone' => '9800000000',
        'shipping_address' => 'Main Road',
        'shipping_city' => 'Kathmandu',
        'subtotal' => 14000.00,
        'shipping_total' => 250.00,
        'total' => 14250.00,
    ]);

    $this->get(route('checkout.confirmation', $order))->assertRedirect(route('login'));

    $this->post(route('checkout.store'), [
        'customer_name' => 'Kasam Thapa',
        'customer_email' => 'kasam@example.com',
        'customer_phone' => '9800000000',
        'shipping_address' => 'Main Road',
        'shipping_city' => 'Kathmandu',
    ])->assertRedirect(route('login'));
});

test('an authenticated shopper can place an order', function () {
    Http::preventStrayRequests();
    Http::fake([
        'dev.khalti.com/api/v2/epayment/initiate/' => Http::response([
            'pidx' => 'test-pidx-123',
            'payment_url' => 'https://test-pay.khalti.com/?pidx=test-pidx-123',
            'expires_at' => '2026-07-15T10:00:00+05:45',
            'expires_in' => 1800,
        ]),
    ]);

    $user = User::factory()->create();
    $category = Category::create([
        'name' => 'Optical Frames',
        'slug' => 'optical-frames',
        'description' => 'Everyday eyewear.',
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Test Frame',
        'slug' => 'test-frame',
        'description' => 'A lightweight frame for testing.',
        'image_url' => 'https://example.com/frame.jpg',
        'price' => 7000.00,
        'stock' => 10,
        'sizes' => ['Medium', 'Wide'],
        'colors' => ['Black'],
        'is_featured' => true,
        'is_active' => true,
    ]);

    $this->post(route('cart.store', $product), [
        'size' => 'Medium',
        'color' => 'Black',
        'quantity' => 2,
    ])->assertRedirect(route('cart.index'));

    $this->actingAs($user)->get(route('checkout.create'))
        ->assertSuccessful()
        ->assertSee('Where should we send your frames?');

    $this->actingAs($user)->post(route('checkout.store'), [
        'customer_name' => 'Kasam Thapa',
        'customer_email' => 'kasam@example.com',
        'customer_phone' => '9800000000',
        'shipping_address' => 'Main Road',
        'shipping_city' => 'Kathmandu',
    ])->assertRedirect('https://test-pay.khalti.com/?pidx=test-pidx-123');

    $this->assertDatabaseHas('orders', [
        'customer_email' => 'kasam@example.com',
        'total' => 14250.00,
        'payment_status' => 'initiated',
        'khalti_pidx' => 'test-pidx-123',
        'khalti_amount' => 1425000,
    ]);

    Http::assertSent(function (Request $request): bool {
        return $request->url() === 'https://dev.khalti.com/api/v2/epayment/initiate/'
            && $request->hasHeader('Authorization', 'Key '.config('services.khalti.secret_key'))
            && $request['amount'] === 1425000
            && $request['customer_info']['email'] === 'kasam@example.com';
    });
});

test('a khalti callback verifies payment before confirming the order', function () {
    Http::preventStrayRequests();
    Http::fake([
        'dev.khalti.com/api/v2/epayment/lookup/' => Http::response([
            'pidx' => 'paid-pidx-123',
            'total_amount' => 1425000,
            'status' => 'Completed',
            'transaction_id' => 'txn-123',
            'fee' => 0,
            'refunded' => false,
        ]),
    ]);

    $user = User::factory()->create();

    session([
        'cart' => [
            'items' => [
                'frame-key' => [
                    'quantity' => 1,
                ],
            ],
        ],
    ]);

    $order = Order::create([
        'order_number' => 'LUM-260715-PAID',
        'customer_name' => 'Kasam Thapa',
        'customer_email' => 'kasam@example.com',
        'customer_phone' => '9800000000',
        'shipping_address' => 'Main Road',
        'shipping_city' => 'Kathmandu',
        'subtotal' => 14000.00,
        'shipping_total' => 250.00,
        'total' => 14250.00,
        'status' => 'payment_pending',
        'payment_status' => 'initiated',
        'khalti_pidx' => 'paid-pidx-123',
        'khalti_amount' => 1425000,
    ]);

    $this->actingAs($user)->get(route('khalti.callback', [
        'order' => $order,
        'pidx' => 'paid-pidx-123',
        'status' => 'Completed',
        'amount' => 1425000,
        'transaction_id' => 'txn-123',
    ]))->assertRedirect(route('checkout.confirmation', $order));

    $order->refresh();

    expect($order->payment_status)->toBe('paid')
        ->and($order->status)->toBe('confirmed')
        ->and($order->khalti_transaction_id)->toBe('txn-123')
        ->and(session('cart'))->toBeNull();
});

test('a failed khalti callback keeps the order unconfirmed', function () {
    Http::preventStrayRequests();
    Http::fake([
        'dev.khalti.com/api/v2/epayment/lookup/' => Http::response([
            'pidx' => 'canceled-pidx-123',
            'total_amount' => 1425000,
            'status' => 'User canceled',
            'transaction_id' => null,
            'fee' => 0,
            'refunded' => false,
        ]),
    ]);

    $user = User::factory()->create();

    $order = Order::create([
        'order_number' => 'LUM-260715-CANCEL',
        'customer_name' => 'Kasam Thapa',
        'customer_email' => 'kasam@example.com',
        'customer_phone' => '9800000000',
        'shipping_address' => 'Main Road',
        'shipping_city' => 'Kathmandu',
        'subtotal' => 14000.00,
        'shipping_total' => 250.00,
        'total' => 14250.00,
        'status' => 'payment_pending',
        'payment_status' => 'initiated',
        'khalti_pidx' => 'canceled-pidx-123',
        'khalti_amount' => 1425000,
    ]);

    $this->actingAs($user)->get(route('khalti.callback', [
        'order' => $order,
        'pidx' => 'canceled-pidx-123',
        'status' => 'User canceled',
        'amount' => 1425000,
    ]))->assertRedirect(route('cart.index'));

    $order->refresh();

    expect($order->payment_status)->toBe('user_canceled')
        ->and($order->status)->toBe('payment_failed')
        ->and($order->paid_at)->toBeNull();
});

test('a visitor can sign up, logout, and login', function () {
    $this->get(route('register'))
        ->assertSuccessful()
        ->assertSee('Sign up');

    $this->post(route('register'), [
        'name' => 'Maya Lens',
        'email' => 'maya@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('products.index'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'maya@example.com',
    ]);

    $this->post(route('logout'))->assertRedirect(route('products.index'));
    $this->assertGuest();

    $this->post(route('login'), [
        'email' => 'maya@example.com',
        'password' => 'password123',
    ])->assertRedirect(route('products.index'));

    $this->assertAuthenticatedAs(User::where('email', 'maya@example.com')->first());
});
