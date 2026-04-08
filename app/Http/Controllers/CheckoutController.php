<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Address;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    /**
     * Process the checkout and create a Stripe Payment Session
     */
    public function processCheckout(Request $request)
    {
        // 1. Initialize Stripe with Secret Key from environment variables
        Stripe::setApiKey(config('services.stripe.secret'));

        $totalAmount = 0;
        $lineItems = [];
        $orderItemsData = []; // Buffer to store data for order_items table later

        // 2. Check for Product ID (Used for Direct/Express Checkout)
        // Ensure the ID exists and is not a "null" string passed from JS
        $productId = $request->product_id;
        $isDirectCheckout = ($productId && $productId !== "null");

        if ($isDirectCheckout) {
            // --- CASE: DIRECT CHECKOUT (Buy It Now) ---
            $product = Product::find($productId);
            if (!$product) {
                return response()->json(['error' => 'Product not found.'], 404);
            }

            $totalAmount = $product->price;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'idr',
                    'product_data' => [
                        'name' => $product->name,
                        'description' => 'Direct Purchase',
                    ],
                    'unit_amount' => $product->price * 100, // Stripe uses subunit (cents/per hundred)
                ],
                'quantity' => 1,
            ];

            $orderItemsData[] = [
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price
            ];
        } else {
            // --- CASE: FROM CART (Standard Checkout) ---
            $cart = session('cart', []);
            if (empty($cart)) {
                return response()->json(['error' => 'Your cart is empty.'], 400);
            }

            foreach ($cart as $id => $details) {
                // Re-validate against Database to ensure product availability
                $dbProduct = Product::find($id);
                if (!$dbProduct) continue;

                $totalAmount += $details['price'] * $details['quantity'];
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'idr',
                        'product_data' => ['name' => $details['name']],
                        'unit_amount' => $details['price'] * 100,
                    ],
                    'quantity' => $details['quantity'],
                ];

                $orderItemsData[] = [
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price']
                ];
            }
        }

        // 3. Shipping Address Logic
        if ($request->selected_address === 'default') {
            $user = Auth::user();
            $name = $user->full_name;
            $phone = $user->phone_number;
            $address = $user->address_detail;
            $city = $user->city;
            $province = $user->province;
        } else {
            $addr = Address::find($request->selected_address);
            if (!$addr) {
                return response()->json(['error' => 'Selected shipping address not found.'], 404);
            }
            $name = $addr->receiver_name;
            $phone = $addr->phone_number;
            $address = $addr->address_detail;
            $city = $addr->city;
            $province = $addr->province;
        }

        // 4. Persist Data to Orders Table
        $order = Order::create([
            'user_id' => Auth::id(),
            'receiver_name' => $name,
            'phone_number' => $phone,
            'address_detail' => $address,
            'city' => $city,
            'province' => $province,
            'total_amount' => $totalAmount,
            'status' => 'pending', // Initial status
        ]);

        // 5. Persist Product Details to OrderItems Table
        foreach ($orderItemsData as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // 6. Create Stripe Checkout Session
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                // redirect to catalog with success flags for post-payment handling
                'success_url' => url('/catalog?payment=success&order_id=' . $order->id),
                'cancel_url' => url('/checkout'),
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ],
            ]);

            return response()->json(['url' => $session->url]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Stripe Error: ' . $e->getMessage()], 500);
        }
    }
}