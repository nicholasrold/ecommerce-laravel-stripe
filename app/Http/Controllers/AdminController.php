<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Display the main admin dashboard
     */
    public function index()
    {
        // Fetch latest products
        $products = Product::latest()->get();
        
        // Fetch orders with related items and product details
        $orders = Order::with(['items.product'])->latest()->get();
        
        return view('admin.dashboard', compact('products', 'orders'));
    }

    /**
     * Store a newly created product
     * (Stock is automatically set to 999)
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $filename = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Generate unique filename: slug-timestamp.extension
            $filename = Str::slug($request->name) . '-' . time() . '.' . $file->getClientOriginalExtension();
            
            // Save file to storage/app/public/products
            $file->storeAs('products', $filename, 'public');
        }

        Product::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'image'       => $filename,
            'price'       => $request->price,
            'stock'       => 999, // Default high stock
            'description' => '-',   // Default placeholder
        ]);

        return back()->with('success', 'New drop published.');
    }

    /**
     * Remove the specified product from storage
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Delete image file from storage to free up disk space
        if ($product->image) {
            Storage::disk('public')->delete('products/' . $product->image);
        }

        $product->delete();

        return back()->with('success', 'Product removed from database.');
    }

    /**
     * Update the shipping status of an order
     */
    public function updateShipping(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $order->update([
            'shipping_status' => 'DELIVERED'
        ]);

        return back()->with('success', 'Package status: DELIVERED.');
    }
}