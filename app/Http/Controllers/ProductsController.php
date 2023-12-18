<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::all();
        return view('pages.products', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new Products;
        $product->product_name = $request->product_name;
        $product->brand = $request->brand;
        $product->price = $request->price;
        $product->stock_quantity = $request->stock_quantity;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->rate = $request->rate;
        $product->reviews_num = $request->reviews_num;
        $product->sold = $request->sold;

        $product->image_url_1 = $request->input('image_urls')[0] ?? $product->image_url_1;
        $product->image_url_2 = $request->input('image_urls')[1] ?? $product->image_url_2;
        $product->image_url_3 = $request->input('image_urls')[2] ?? $product->image_url_3;
        $product->image_url_4 = $request->input('image_urls')[3] ?? $product->image_url_4;
        $product->image_url_5 = $request->input('image_urls')[4] ?? $product->image_url_5;

        if($product->save()) {
            return response([
            'message' => 'Added new Product!'
        ]);
        }else {
            return false;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the product by ID
    $product = Products::find($id);

    // Check if the product exists
    if (!$product) {
        return response()->json([
            "message" => "Product not found",
        ], 404);
    }

    // Validate the request data
    $fields = $request->validate([
        'brand' => 'required|string',
        'product_name' => 'required|string',
        'price' => 'required|numeric',
        'stock_quantity' => 'required|integer',
        'description' => 'required|string',
        'category' => 'required|string',
        'rate' => 'required|numeric',
        'reviews_num' => 'required|integer',
        'sold' => 'required|integer',
        'image_url_1' => 'nullable|string',
        'image_url_2' => 'nullable|string',
        'image_url_3' => 'nullable|string',
        'image_url_4' => 'nullable|string',
        'image_url_5' => 'nullable|string',
    ]);

    // Update the product with the validated data
    $product->update($fields);

    // You can handle the image URLs separately if needed
    // For example, update each image URL one by one
    $product->image_url_1 = $request->input('image_urls')[0] ?? $product->image_url_1;
    $product->image_url_2 = $request->input('image_urls')[1] ?? $product->image_url_2;
    $product->image_url_3 = $request->input('image_urls')[2] ?? $product->image_url_3;
    $product->image_url_4 = $request->input('image_urls')[3] ?? $product->image_url_4;
    $product->image_url_5 = $request->input('image_urls')[4] ?? $product->image_url_5;

    $product->save();

    return response()->json([
        "message" => "Product updated successfully",
        "product" => $product,
    ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Products $products)
    {
        $products->delete();

        return response([
            'message' => 'Product Deleted!'
        ]);
    }

    public function deleteProduct($id) {
        if(Products::find($id)) {
            $Products = Products::find($id);
            $Products->delete();

            return response()->json([
                "message" => "Blog deleted"
            ], 202);
        }else{
            return response()->json([
                "message" => "Blog not found"
            ], 404);
        }
    }

    public function getAllProducts() {
        $products = Products::all();
        return response()->json($products);
    }

    public function getProduct($id) {
        $product = Products::where('id', $id)->first();
        return response()->json($product);
    }
}
