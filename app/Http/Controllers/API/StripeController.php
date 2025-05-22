<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Models\Employer;
use App\Models\Usersjob;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use Illuminate\Support\Facades\Hash;

class StripeController extends Controller
{

public function createCheckoutSession(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    // بيانات الدفع
    $amount = 1000; // بالسنتات (يعني 10 دولار)
    $employerId = $request->employer_id;
    $jobId = $request->job_id;

    // إنشاء سجل الدفع في قاعدة البيانات
    $payment = Payment::create([
        'stripe_payment_id' => '', // هيتحدث لاحقًا
        'user_id' => auth()->id(), // أو $request->user_id حسب النظام
        'employer_id' => $employerId,
        'job_id' => $jobId,
        'amount' => $amount / 100,
        'currency' => 'usd',
        'payment_method' => 'card',
        'status' => 'pending',
    ]);

    // إنشاء جلسة الدفع
    $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => 'Job Promotion', // أو اسم الوظيفة
                ],
                'unit_amount' => $amount,
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => url('/payment-success'),
        'cancel_url' => url('/payment-cancel'),
        'metadata' => [
            'payment_id' => $payment->id,
        ]
    ]);

return response()->json([
    'status' => 'success',
    'message' => 'Stripe session created successfully',
    'checkout_url' => $session->url,
    'payment_id' => $payment->id // فقط لو محتاجاه
], 201);

}


public function handleWebhook(Request $request)
{
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $payload = $request->getContent();
    $sig_header = $request->header('Stripe-Signature');
    $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

    try {
        $event = Webhook::constructEvent(
            $payload, $sig_header, $endpoint_secret
        );
    } catch (\Exception $e) {
        Log::error('Stripe webhook error: ' . $e->getMessage());
        return response('Webhook Error', 400);
    }

    if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;

        // الحصول على payment_id من metadata
        $paymentId = $session->metadata->payment_id ?? null;

        if ($paymentId) {
            $payment = Payment::find($paymentId);
            if ($payment) {
                $payment->update([
                    'stripe_payment_id' => $session->payment_intent,
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }
        }
    }

    return response('Webhook received', 200);
}

}
