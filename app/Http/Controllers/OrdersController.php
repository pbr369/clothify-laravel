<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::all();
        return view('pages.orders', ['orders' => $orders]);
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
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orders $orders)
    {
        //
    }

    public function getAllOrders() {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'User not authenticated.'], 401);
        }

        $user = Auth::user();
        $userId = $user->id;

        // Ensure that user ID is available
        if (!$userId) {
            return response()->json(['error' => 'User ID not found.'], 500);
        }

        $orders = Orders::where('user_id', $userId)->get();
        return response()->json($orders);
    }

    public function getOrder($id) {
        $user = Auth::user();
        $userId = $user->id;

        $order = Orders::where('id', $id)->where('user_id', $userId)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found or does not belong to the user.'], 404);
        }

        return response()->json($order);
    }

    public function toShipOrders(Request $request) {
        $user = Auth::user();
        $userId = $user->id;

        $selectedCategory = $request->input('category', 'To Ship');

        $orders = Orders::where('status', $selectedCategory)
            ->where('id', $userId)
            ->get();

        return view('pages.orders', ['orders' => $orders, 'selectedCategory' => $selectedCategory]);
    }

    public function toShipReceiveOrders(Request $request) {
        $user = Auth::user();
        $userId = $user->id;

        $selectedCategory = $request->input('category', 'To Receive');

        $orders = Orders::where('status', $selectedCategory)
            ->where('id', $userId)
            ->get();

        return view('pages.orders', ['orders' => $orders, 'selectedCategory' => $selectedCategory]);
    }

    public function completedOrders(Request $request) {
        $user = Auth::user();
        $userId = $user->id;

        $selectedCategory = $request->input('category', 'Completed');

        $orders = Orders::where('status', $selectedCategory)
            ->where('id', $userId)
            ->get();

        return view('pages.orders', ['orders' => $orders, 'selectedCategory' => $selectedCategory]);
    }

    public function publicGetAllOrders($category = null) {
        $query = Orders::query();

    if ($category) {
        $query->where('status', $category);
    }

    $orders = $query->get();

    return response()->json($orders);
    }

    public function publicGetOrder($id) {
        $order = Orders::find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found.'], 404);
        }

        return response()->json($order);
    }

    public function markAsShipped(Request $request, $id) {
    $order = Orders::find($id);

    if (!$order) {
        return response()->json(['error' => 'Order not found.'], 404);
    }

    $order->status = 'toreceive';
    $order->save();

    return response()->json(['message' => 'Order marked as shipped successfully.']);
}

public function cancelOrder(Request $request, $id) {
    $order = Orders::find($id);

    if (!$order) {
        return response()->json(['error' => 'Order not found.'], 404);
    }

    $order->status = 'cancelled';
    $order->save();

    return response()->json(['message' => 'Order cancelled successfully.']);
}
public function completedOrder(Request $request, $id) {
    $order = Orders::find($id);

    if (!$order) {
        return response()->json(['error' => 'Order not found.'], 404);
    }

    $order->status = 'completed';
    $order->save();

    return response()->json(['message' => 'Order marked as shipped successfully.']);
}

}
