<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Requests\OrderRequest;
use App\Models\AddStudent;
class OrderController extends Controller
{
    public function createOrder(OrderRequest $request)
    {
        // Validate the request data using OrderRequest
        $validatedData = $request->validated();
        
        // Create a new Order using the validated data
        $order = Order::create($validatedData);

        $addStuent = new AddStudent();
        $addStuent->name;
        
        // Return a success response with the created order details
        return response()->json([
            'success' => true,
            'message' => 'Order created successfully.',
            'order' => $order
        ], 201);
    }
}
