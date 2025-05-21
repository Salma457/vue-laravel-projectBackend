<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    public function createStripeSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Job Posting'],
                    'unit_amount' => $request->amount * 100, // cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $request->success_url . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $request->cancel_url,
        ]);

        // Save pending payment
        $payment = Payment::create([
            'employer_id' => $request->employer_id,
            'job_id' => $request->job_id,
            'amount' => $request->amount,
            'payment_method' => 'stripe',
            'status' => 'pending',
            'transaction_id' => $session->id,
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function stripeSuccess(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = \Stripe\Checkout\Session::retrieve($request->session_id);

        $payment = Payment::where('transaction_id', $session->id)->first();
        if ($payment) {
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Payment successful']);
    }
}
