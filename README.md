# Luma Lens

Independent eyewear commerce for Nepal — a Laravel storefront with a Khalti-powered
checkout, an interactive 3D product viewer, and a lightweight admin console for
managing the catalog and fulfilling orders.

## Features

**Storefront**
- Product catalog with live search, category filtering, and pagination
- Dedicated `/shop` page for fast, no-frills browsing alongside a marketing-led homepage
- Interactive 3D product viewer (Three.js) for a hands-on try-before-you-buy feel
- Session-based cart with stock-aware quantity limits (no overselling sold-out or low-stock items)
- Checkout with [Khalti](https://khalti.com) ePayment integration — server-side payment verification via Khalti's lookup API, not just a client-side redirect
- Stock is decremented atomically on confirmed payment

**Admin console** (`/admin`, gated by an `is_admin` flag on the user)
- Dashboard: total orders, revenue, orders awaiting delivery, low-stock count
- Full product CRUD (create, edit, delete, feature/hide)
- Order management: view line items, customer & shipping details, and update fulfillment status

## Tech stack

| Layer       | Choice                                      |
|-------------|----------------------------------------------|
| Backend     | Laravel 13, PHP 8.3                          |
| Database    | SQLite (default), Eloquent ORM               |
| Frontend    | Blade, Tailwind CSS v4, Vite                 |
| 3D          | Three.js                                     |
| Payments    | Khalti ePayment (KPG-2)                      |
| Testing     | Pest 4                                       |

## Getting started

Requires PHP 8.3+, Composer, and Node 18+.

```bash
composer setup
```

This installs PHP and JS dependencies, copies `.env.example` to `.env`, generates
an app key, runs migrations, and builds frontend assets.

To run the app in development (server, queue worker, log tailer, and Vite, all
concurrently):

```bash
composer dev
```

The app will be available at `http://127.0.0.1:8000`.

### Khalti payment testing

Add sandbox credentials to `.env` to exercise the checkout flow:

```env
KHALTI_BASE_URL=https://dev.khalti.com/api/v2
KHALTI_PUBLIC_KEY=
KHALTI_SECRET_KEY=
```

### Creating an admin user

`is_admin` is intentionally excluded from mass assignment, so it can't be set
through a form. Grant access directly:

```bash
php artisan tinker --execute '
$user = \App\Models\User::where("email", "you@example.com")->first();
$user->is_admin = true;
$user->save();
'
```

Then log in and visit `/admin`.

## Testing

```bash
composer test
```

Runs the Pest suite, covering the catalog, cart, full checkout flow (with Khalti
mocked via `Http::fake()`), and authentication.

## Code style

```bash
vendor/bin/pint
```

## License

Open-sourced under the [MIT license](LICENSE).
