<?php

use App\Livewire\AddToCartForm;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeRxProduct(array $overrides = []): Product
{
    $category = Category::firstOrCreate(
        ['slug' => 'optical-frames-rx'],
        ['name' => 'Optical Frames Rx', 'description' => 'Everyday eyewear.'],
    );

    return Product::create([
        'category_id' => $category->id,
        'name' => 'Rx Test Frame',
        'slug' => 'rx-test-frame-'.uniqid(),
        'description' => 'A frame for testing prescription capture.',
        'image_url' => 'https://example.com/frame.jpg',
        'price' => 7000.00,
        'stock' => 10,
        'sizes' => ['Medium'],
        'colors' => ['Black'],
        'is_featured' => false,
        'is_active' => true,
        'requires_prescription' => true,
        ...$overrides,
    ]);
}

function makeNonRxProduct(array $overrides = []): Product
{
    $category = Category::firstOrCreate(
        ['slug' => 'sunglasses-norx'],
        ['name' => 'Sunglasses NoRx', 'description' => 'Sun-ready frames.'],
    );

    return Product::create([
        'category_id' => $category->id,
        'name' => 'No-Rx Test Frame',
        'slug' => 'norx-test-frame-'.uniqid(),
        'description' => 'A frame that does not need a prescription.',
        'image_url' => 'https://example.com/frame.jpg',
        'price' => 5000.00,
        'stock' => 10,
        'sizes' => ['Medium'],
        'colors' => ['Black'],
        'is_featured' => false,
        'is_active' => true,
        'requires_prescription' => false,
        ...$overrides,
    ]);
}

test('a prescription-requiring product cannot be added to cart without choosing a prescription path', function () {
    $product = makeRxProduct();

    Livewire::test(AddToCartForm::class, ['product' => $product])
        ->call('addToCart')
        ->assertHasErrors(['prescriptionStatus' => 'required']);
});

test('the "provide it later" path adds to cart without any numeric fields', function () {
    $product = makeRxProduct();

    Livewire::test(AddToCartForm::class, ['product' => $product])
        ->set('prescriptionStatus', 'later')
        ->call('addToCart')
        ->assertHasNoErrors()
        ->assertSet('added', true);

    $item = collect(session('cart.items'))->first();

    expect($item['prescription'])->toBe(['status' => 'later']);
});

test('a full prescription is validated and stored per line item', function () {
    $product = makeRxProduct();

    Livewire::test(AddToCartForm::class, ['product' => $product])
        ->set('prescriptionStatus', 'provided')
        ->set('sphRight', '-1.25')
        ->set('sphLeft', '-1.50')
        ->set('cylRight', '-0.50')
        ->set('axisRight', '90')
        ->set('pd', '62')
        ->call('addToCart')
        ->assertHasNoErrors()
        ->assertSet('added', true);

    $item = collect(session('cart.items'))->first();

    expect($item['prescription'])->toBe([
        'status' => 'provided',
        'sph_right' => -1.25,
        'sph_left' => -1.5,
        'cyl_right' => -0.5,
        'cyl_left' => null,
        'axis_right' => 90,
        'axis_left' => null,
        'pd' => 62.0,
    ]);
});

test('pd outside the sane range is rejected', function () {
    $product = makeRxProduct();

    Livewire::test(AddToCartForm::class, ['product' => $product])
        ->set('prescriptionStatus', 'provided')
        ->set('sphRight', '-1.00')
        ->set('sphLeft', '-1.00')
        ->set('pd', '110')
        ->call('addToCart')
        ->assertHasErrors(['pd' => 'between']);
});

test('a cylinder value without an axis is rejected', function () {
    $product = makeRxProduct();

    Livewire::test(AddToCartForm::class, ['product' => $product])
        ->set('prescriptionStatus', 'provided')
        ->set('sphRight', '-1.00')
        ->set('sphLeft', '-1.00')
        ->set('pd', '62')
        ->set('cylLeft', '-0.75')
        ->call('addToCart')
        ->assertHasErrors(['axisLeft' => 'required_with']);
});

test('a product that does not require a prescription skips the prescription form entirely', function () {
    $product = makeNonRxProduct();

    Livewire::test(AddToCartForm::class, ['product' => $product])
        ->assertDontSee('Prescription')
        ->call('addToCart')
        ->assertHasNoErrors()
        ->assertSet('added', true);

    $item = collect(session('cart.items'))->first();

    expect($item['prescription'])->toBeNull();
});

test('the raw cart store endpoint enforces the same prescription rules', function () {
    $product = makeRxProduct();

    $this->post(route('cart.store', $product), [
        'quantity' => 1,
    ])->assertSessionHasErrors(['prescription_status']);

    $this->post(route('cart.store', $product), [
        'quantity' => 1,
        'prescription_status' => 'later',
    ])->assertRedirect(route('cart.index'));

    $item = collect(session('cart.items'))->first();
    expect($item['prescription'])->toBe(['status' => 'later']);
});

test('a prescription-requiring product shows a link to the pdp instead of quick add', function () {
    $product = makeRxProduct();

    $this->get(route('shop'))
        ->assertSuccessful()
        ->assertSee('Select prescription');
});

test('checkout carries the captured prescription through to the order item', function () {
    $user = User::factory()->create();
    $product = makeRxProduct();

    Livewire::test(AddToCartForm::class, ['product' => $product])
        ->set('prescriptionStatus', 'provided')
        ->set('sphRight', '-2.00')
        ->set('sphLeft', '-2.25')
        ->set('pd', '60')
        ->call('addToCart');

    Http::preventStrayRequests();
    Http::fake([
        'dev.khalti.com/api/v2/epayment/initiate/' => Http::response([
            'pidx' => 'test-pidx-rx',
            'payment_url' => 'https://test-pay.khalti.com/?pidx=test-pidx-rx',
            'expires_at' => '2026-07-15T10:00:00+05:45',
            'expires_in' => 1800,
        ]),
    ]);

    $this->actingAs($user)->post(route('checkout.store'), [
        'customer_name' => 'Kasam Thapa',
        'customer_email' => 'kasam@example.com',
        'customer_phone' => '9800000000',
        'shipping_address' => 'Main Road',
        'shipping_city' => 'Kathmandu',
    ])->assertRedirect('https://test-pay.khalti.com/?pidx=test-pidx-rx');

    $order = Order::where('customer_email', 'kasam@example.com')->firstOrFail();
    $orderItem = OrderItem::where('order_id', $order->id)->firstOrFail();

    // Whole-number floats round-trip through the JSON column as PHP ints
    // (e.g. -2.0 comes back as -2), so compare numerically rather than
    // with a strict array match.
    expect($orderItem->prescription['status'])->toBe('provided')
        ->and((float) $orderItem->prescription['sph_right'])->toBe(-2.0)
        ->and((float) $orderItem->prescription['sph_left'])->toBe(-2.25)
        ->and($orderItem->prescription['cyl_right'])->toBeNull()
        ->and($orderItem->prescription['cyl_left'])->toBeNull()
        ->and($orderItem->prescription['axis_right'])->toBeNull()
        ->and($orderItem->prescription['axis_left'])->toBeNull()
        ->and((float) $orderItem->prescription['pd'])->toBe(60.0);
});

test('an admin can toggle whether a product requires a prescription', function () {
    $admin = User::factory()->create();
    $admin->is_admin = true;
    $admin->save();

    $category = Category::firstOrCreate(
        ['slug' => 'optical-frames-admin-rx'],
        ['name' => 'Optical Frames Admin Rx', 'description' => 'Everyday eyewear.'],
    );

    $this->actingAs($admin)->post(route('admin.products.store'), [
        'category_id' => $category->id,
        'name' => 'Admin Rx Frame',
        'description' => 'A frame added via the admin form.',
        'image_url' => 'https://example.com/frame.jpg',
        'price' => 8000,
        'stock' => 5,
        'sizes' => 'Medium',
        'colors' => 'Black',
        'requires_prescription' => '1',
    ])->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'name' => 'Admin Rx Frame',
        'requires_prescription' => 1,
    ]);
});
