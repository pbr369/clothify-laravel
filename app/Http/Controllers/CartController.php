<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }

    public function addToCart(Request $request)
    {
        // Validate and handle the form submission to add a product
        // ...

        // After successfully adding the product, associate it with the authenticated user
        $user = Auth::user();
        $productId = $request->input('product_id');

        // Save the product for the authenticated user
        $user->products()->attach($productId);

        // Redirect or return a response as needed
        // ...
    }

    public function userProducts()
    {
        // Retrieve and display products associated with the authenticated user
        $userProducts = Auth::user()->products;

        // Pass $userProducts to the view to render the user's products
        return view('user_products', compact('userProducts'));
    }
}
