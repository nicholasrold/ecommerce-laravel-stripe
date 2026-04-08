<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle incoming Stripe Webhook events
     */
    public function handleStripe(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            // Verify the event signature to ensure it came from Stripe
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\Exception $e) {
            // Return error if signature verification fails
            return response()->json(['error' => $e->getMessage()], 400);
        }

        /**
         * Logic for successful payment
         * Event: checkout.session.completed
         */
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id;

            $order = Order::find($orderId);
            
            if ($order) {
                // Update order status to paid and store Stripe session ID
                $order->update([
                    'status' => 'paid',
                    'stripe_session_id' => $session->id
                ]);
                
                Log::info("Order ID {$orderId} has been PAID via Webhook.");
            }
        }

        return response()->json(['status' => 'success']);
    }
}