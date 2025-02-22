<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(){
        $this->authorize('admin');

        return response()->json(['message' => 'Dashboard']);
    }

    public function users(){
        $users = User::all()->paginate(10);

        return response($users);
    }

    public function orders()
    {
        $orders = Order::all()->paginate(10);
        return response()->json($orders);
    }
    public function showOrder($id)
    {
        $order = Order::find($id);
        return response()->json($order);
    }
    public function updateOrder(Request $request, $id)
    {
        $order = Order::find($id);
        $order->update($request->all());
        return response()->json($order);
    }

    public function deleteOrder($id){
        $order = Order::find($id);
        $order->delete();
        return response()->json(['message' => 'Order deleted']);
    
    }
}
