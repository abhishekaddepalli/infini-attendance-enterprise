<?php

declare(strict_types=1);

namespace Infini\Attendance\Services;

use Razorpay\Api\Api as RazorpayApi;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class PaymentGatewayService
{
    private array $gateways = [];
    private ?RazorpayApi $razorpay = null;
    private ?StripeClient $stripe = null;

    public function __construct()
    {
        $this->initializeGateways();
    }

    private function initializeGateways(): void
    {
        if (config('services.razorpay.key_id') && config('services.razorpay.key_secret')) {
            $this->razorpay = new RazorpayApi(
                config('services.razorpay.key_id'),
                config('services.razorpay.key_secret')
            );
            $this->gateways['razorpay'] = [
                'name' => 'Razorpay',
                'region' => 'India',
                'currencies' => ['INR'],
                'methods' => ['upi', 'card', 'netbanking', 'wallet', 'emi'],
                'enabled' => true,
            ];
        }

        if (config('services.stripe.secret')) {
            $this->stripe = new StripeClient(config('services.stripe.secret'));
            $this->gateways['stripe'] = [
                'name' => 'Stripe',
                'region' => 'International',
                'currencies' => ['USD', 'EUR', 'GBP', 'AED'],
                'methods' => ['card', 'apple_pay', 'google_pay'],
                'enabled' => true,
            ];
        }
    }

    public function getAvailableGateways(): array
    {
        return $this->gateways;
    }

    public function createOrder(string $gateway, array $data): array
    {
        return match ($gateway) {
            'razorpay' => $this->createRazorpayOrder($data),
            'stripe' => $this->createStripePaymentIntent($data),
            default => throw new \InvalidArgumentException("Unknown gateway: $gateway"),
        };
    }

    private function createRazorpayOrder(array $data): array
    {
        $amount = $this->calculateAmount($data['plan'], $data['billing']);

        $order = $this->razorpay->order->create([
            'receipt' => 'INF-' . strtoupper(uniqid()),
            'amount' => $amount * 100,
            'currency' => 'INR',
            'notes' => [
                'plan' => $data['plan'],
                'billing' => $data['billing'],
                'tenant_id' => $data['tenant_id'],
            ],
        ]);

        return [
            'order_id' => $order->id,
            'amount' => $amount,
            'currency' => 'INR',
            'key' => config('services.razorpay.key_id'),
        ];
    }

    private function createStripePaymentIntent(array $data): array
    {
        $amount = $this->calculateAmount($data['plan'], $data['billing']);

        $paymentIntent = $this->stripe->paymentIntents->create([
            'amount' => $amount * 100,
            'currency' => 'usd',
            'metadata' => [
                'plan' => $data['plan'],
                'billing' => $data['billing'],
                'tenant_id' => $data['tenant_id'],
            ],
            'description' => "Infini Attendance - {$data['plan']} Plan ({$data['billing']})",
        ]);

        return [
            'client_secret' => $paymentIntent->client_secret,
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $amount,
            'currency' => 'usd',
        ];
    }

    public function verifyPayment(string $gateway, array $data): bool
    {
        return match ($gateway) {
            'razorpay' => $this->verifyRazorpayPayment($data),
            'stripe' => true, // Stripe uses webhooks
            default => false,
        };
    }

    private function verifyRazorpayPayment(array $data): bool
    {
        try {
            $attributes = [
                'razorpay_order_id' => $data['razorpay_order_id'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature'],
            ];
            $this->razorpay->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (\Exception $e) {
            Log::error('Payment verification failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function calculateAmount(string $plan, string $billing): float
    {
        $plans = config('infini.subscription.plans');
        $planConfig = $plans[$plan] ?? $plans['starter'];

        return $billing === 'yearly' ? $planConfig['yearly'] : $planConfig['monthly'];
    }

    public function processRefund(string $gateway, string $paymentId, float $amount): array
    {
        try {
            if ($gateway === 'razorpay') {
                $refund = $this->razorpay->payment->fetch($paymentId)->refund([
                    'amount' => $amount * 100,
                ]);
                return ['refund_id' => $refund->id, 'status' => $refund->status];
            }

            if ($gateway === 'stripe') {
                $refund = $this->stripe->refunds->create([
                    'payment_intent' => $paymentId,
                    'amount' => $amount * 100,
                ]);
                return ['refund_id' => $refund->id, 'status' => $refund->status];
            }
        } catch (\Exception $e) {
            Log::error('Refund failed', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }

        return ['error' => 'Gateway not supported'];
    }
}
