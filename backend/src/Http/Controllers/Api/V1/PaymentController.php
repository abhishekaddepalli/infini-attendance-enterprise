<?php

namespace Infini\Attendance\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PaymentController extends Controller
{
    public function getGateways(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'gateways' => ['razorpay', 'stripe', 'paypal', 'phonepe']
        ]);
    }

    public function createRazorpayOrder(Request $request)
    {
        return response()->json(['status' => 'success', 'order_id' => 'order_' . uniqid()]);
    }

    public function verifyRazorpayPayment(Request $request)
    {
        return response()->json(['status' => 'success', 'verified' => true]);
    }

    public function createStripePaymentIntent(Request $request)
    {
        return response()->json(['status' => 'success', 'client_secret' => 'pi_mock_secret']);
    }

    public function applyCoupon(Request $request)
    {
        return response()->json(['status' => 'success', 'discount' => 10]);
    }

    public function getBillingHistory(Request $request)
    {
        return response()->json(['status' => 'success', 'history' => []]);
    }

    public function downloadInvoice(Request $request, $invoice)
    {
        return response()->json(['status' => 'success', 'download_url' => '']);
    }

    public function cancelSubscription(Request $request)
    {
        return response()->json(['status' => 'success', 'message' => 'Subscription cancelled']);
    }

    public function handleStripeWebhook(Request $request)
    {
        return response()->json(['status' => 'received']);
    }

    public function handleRazorpayWebhook(Request $request)
    {
        return response()->json(['status' => 'received']);
    }
}
