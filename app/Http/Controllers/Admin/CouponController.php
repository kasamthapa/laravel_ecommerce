<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        return view('admin.coupons.index', [
            'coupons' => Coupon::latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['code'] = strtoupper($validated['code']);

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('status', "Coupon {$validated['code']} was created.");
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.edit', [
            'coupon' => $coupon,
        ]);
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $this->validated($request, $coupon);
        $validated['code'] = strtoupper($validated['code']);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('status', "Coupon {$coupon->code} was updated.");
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('status', "Coupon {$coupon->code} was deleted.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Coupon $coupon = null): array
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:40', Rule::unique('coupons', 'code')->ignore($coupon)],
            'type' => ['required', Rule::in(Coupon::TYPES)],
            'value' => ['required', 'numeric', 'min:0'],
            'max_uses' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
