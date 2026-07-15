<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class KhaltiPaymentGateway
{
    /**
     * @return array{pidx: string, payment_url: string, expires_at?: string, expires_in?: int}
     */
    public function initiate(Order $order, string $returnUrl, string $websiteUrl): array
    {
        $response = $this->client()
            ->post('/epayment/initiate/', [
                'return_url' => $returnUrl,
                'website_url' => $websiteUrl,
                'amount' => $this->amountInPaisa($order),
                'purchase_order_id' => $order->order_number,
                'purchase_order_name' => 'Luma Lens order '.$order->order_number,
                'customer_info' => [
                    'name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                ],
                'amount_breakdown' => [
                    [
                        'label' => 'Subtotal',
                        'amount' => $this->rupeesToPaisa((float) $order->subtotal),
                    ],
                    [
                        'label' => 'Shipping',
                        'amount' => $this->rupeesToPaisa((float) $order->shipping_total),
                    ],
                ],
                'product_details' => $order->orderItems->map(fn ($item): array => [
                    'identity' => (string) ($item->product_id ?? $item->id),
                    'name' => $item->product_name,
                    'total_price' => $this->rupeesToPaisa((float) $item->line_total),
                    'quantity' => $item->quantity,
                    'unit_price' => $this->rupeesToPaisa((float) $item->unit_price),
                ])->values()->all(),
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Khalti payment initiation failed.');
        }

        /** @var array{pidx?: string, payment_url?: string, expires_at?: string, expires_in?: int} $payload */
        $payload = $response->json();

        if (! isset($payload['pidx'], $payload['payment_url'])) {
            throw new RuntimeException('Khalti returned an invalid initiation response.');
        }

        return [
            'pidx' => $payload['pidx'],
            'payment_url' => $payload['payment_url'],
            'expires_at' => $payload['expires_at'] ?? null,
            'expires_in' => $payload['expires_in'] ?? null,
        ];
    }

    /**
     * @return array{pidx: string, total_amount: int, status: string, transaction_id?: string|null, fee?: int, refunded?: bool}
     */
    public function lookup(string $pidx): array
    {
        $response = $this->client()
            ->post('/epayment/lookup/', [
                'pidx' => $pidx,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Khalti payment lookup failed.');
        }

        /** @var array{pidx?: string, total_amount?: int, status?: string, transaction_id?: string|null, fee?: int, refunded?: bool} $payload */
        $payload = $response->json();

        if (! isset($payload['pidx'], $payload['total_amount'], $payload['status'])) {
            throw new RuntimeException('Khalti returned an invalid lookup response.');
        }

        return [
            'pidx' => $payload['pidx'],
            'total_amount' => $payload['total_amount'],
            'status' => $payload['status'],
            'transaction_id' => $payload['transaction_id'] ?? null,
            'fee' => $payload['fee'] ?? null,
            'refunded' => $payload['refunded'] ?? null,
        ];
    }

    public function amountInPaisa(Order $order): int
    {
        return $this->rupeesToPaisa((float) $order->total);
    }

    private function client(): \Illuminate\Http\Client\PendingRequest
    {
        $secretKey = config('services.khalti.secret_key');

        if (! is_string($secretKey) || $secretKey === '') {
            throw new RuntimeException('Khalti secret key is not configured.');
        }

        return Http::baseUrl((string) config('services.khalti.base_url'))
            ->acceptJson()
            ->asJson()
            ->withHeader('Authorization', 'Key '.$secretKey)
            ->retry(2, 200, fn (mixed $exception): bool => $exception instanceof ConnectionException || $exception instanceof RequestException)
            ->connectTimeout(5)
            ->timeout(10);
    }

    private function rupeesToPaisa(float $amount): int
    {
        return (int) round($amount * 100);
    }
}
