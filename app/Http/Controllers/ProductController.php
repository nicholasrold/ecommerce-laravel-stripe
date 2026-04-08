<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Address;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class ProductController extends Controller
{
    /**
     * Display all products in the catalog
     */
    public function index() 
    {
        $products = Product::all();
        return view('catalog', compact('products'));
    }

    /**
     * Add a product to the cart (via AJAX)
     */
    public function addToCart(Request $request, $id) 
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['cart_count' => count($cart)]);
    }

    /**
     * Display the shopping cart page
     */
    public function viewCart() 
    {
        return view('cart');
    }

    /**
     * Update cart item quantity (via AJAX)
     */
    public function updateCart(Request $request) 
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                $cart[$request->id]["quantity"] = $request->quantity;
                session()->put('cart', $cart);
                return response()->json(['success' => true]);
            }
        }
        return response()->json(['success' => false], 400);
    }

    /**
     * Remove an item from the cart
     */
    public function removeFromCart($id) 
    {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Item removed');
    }

    /**
     * Display the checkout page
     */
    public function viewCheckout() 
    {
        $cart = session('cart');
        
        // Protection: If cart is empty, redirect back to catalog
        if(!$cart || count($cart) == 0) {
            return redirect()->route('catalog');
        }
        
        // Fetch additional addresses from the authenticated user
        $addresses = auth()->user()->addresses; 
        
        // Fetch the default address from the address_detail column in users table
        $defaultAddress = auth()->user()->address_detail; 

        return view('checkout', compact('addresses', 'defaultAddress'));
    }

    /**
     * Process payment using Stripe
     */
    public function processCheckout(Request $request) 
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $cart = session('cart');
        $lineItems = [];

        foreach($cart as $id => $details) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'idr',
                    'product_data' => [
                        'name' => $details['name'],
                    ],
                    'unit_amount' => $details['price'] * 100, // Stripe IDR standard library uses subunit (*100)
                ],
                'quantity' => $details['quantity'],
            ];
        }

        // Create a Stripe Checkout session
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('catalog'), // Consider creating a specific success page later
            'cancel_url' => route('cart.view'),
        ]);

        return response()->json(['id' => $session->id]);
    }

    /**
     * Store a new address via AJAX from the checkout page
     */
    public function storeAddress(Request $request) 
    {
        $data = $request->validate([
            'receiver_name' => 'required|string',
            'phone_number' => 'required|string',
            'label' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
            'address_detail' => 'required|string'
        ]);

        auth()->user()->addresses()->create($data);
        return response()->json(['success' => true]);
    }

    /**
     * Delete a specific address
     */
    public function deleteAddress($id) 
    {
        $address = auth()->user()->addresses()->findOrFail($id);
        $address->delete();
        return response()->json(['success' => true]);
    }
}