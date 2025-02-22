<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Log;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $orders = Order::all();
        return response()->json($orders);
    }
    public function store(Request $request){

        $request->validate([
          'item_code' => 'required|exists:items,code',
          'item_name' => 'required|exists:items,item_name',
          'quantity' => 'required|integer|min:1',
          'address' => 'required',
          'amount' => 'required|numeric|min:1',
          'notes' => 'nullable',
          'image' => 'nullable|image|max:2048'
      ]);
      
      $item = Item::find($request->id);
      
      $order = new Order();
      $order->user_id = $request->user_id;
      $order->item_code = $request->item_code;
      $order->item_name = $request->item_name;
      $order->quantity = $request->quantity;
      $order->amount = $request->amount;
      $order->address = $request->address;
      $order->notes = $request->notes;
      
      if ($request->hasFile('image')) {
          $path = $request->file('image')->store('public/');
          $order->image = str_replace('public/', '', $path);
      }
      
      $order->save();
      
      Log::create([
          'user_id' => auth()->id(),
          'action' => 'create_order',
          'description' => 'Order created with ID: ' . $order->id
      ]); 
      
      return response()->json($order, 201);

    }

    public function show($id){
        $order = Order::with('item')->where('id', $id)->first();
        if(!$order) return response()->json(['message' => 'Order not found'], 404);
        return response()->json($order);
    }

    public function edit($id){
        $order = Order::find($id);
        if(!$order) return response()->json(['message' => 'Order not found'], 404);
        return response()->json($order);
    }

    public function update(Request $request, $id){
        $order = Order::find( $id);
        if(!$order) return response()->json(['message' => 'Order not found'], 404);

        $request->validate([
            'item_code' => 'required|exists:items,code',
            'item_name' => 'required|exists:items,item_name',
            'quantity' => 'required|integer|min:1',
            'amount' => 'required',
            'address' => 'required',
            'status' => 'required',
            'notes' => 'required',
            'image' => 'nullable|image|max:2048'
        ]);


        $order->quantity = $request->quantity;
        $order->amount = $request->amount;
        $order->address = $request->address;
        $order->status = $request->status;
        $order->notes = $request->notes;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/orders');
            $order->image = str_replace('public/', '', $path);
        }

        $order->save();

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'update_order',
            'description' => 'Order updated with ID: ' . $order->id
        ]);

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->first();
        if (!$order)
            return response()->json(['message' => 'Order not found'], 404);

        $order->delete();

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'delete_order',
            'description' => 'Order deleted with ID: ' . $order->id
        ]);

        return response()->json(['message' => 'Order deleted']);
    }
}
