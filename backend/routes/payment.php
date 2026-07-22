<?php

use Illuminate\Support\Facades\Route;
use Infini\Attendance\Http\Controllers\Api\V1\PaymentController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/gateways', [PaymentController::class, 'getGateways']);
    Route::post('/razorpay/create-order', [PaymentController::class, 'createRazorpayOrder']);
    Route::post('/razorpay/verify', [PaymentController::class, 'verifyRazorpayPayment']);
    Route::post('/stripe/create-intent', [PaymentController::class, 'createStripePaymentIntent']);
    Route::post('/apply-coupon', [PaymentController::class, 'applyCoupon']);
    Route::get('/billing-history', [PaymentController::class, 'getBillingHistory']);
    Route::get('/invoices/{invoice}/download', [PaymentController::class, 'downloadInvoice']);
    Route::post('/cancel-subscription', [PaymentController::class, 'cancelSubscription']);
});

Route::post('/stripe/webhook', [PaymentController::class, 'handleStripeWebhook']);
Route::post('/razorpay/webhook', [PaymentController::class, 'handleRazorpayWebhook']);
