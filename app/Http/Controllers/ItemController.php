<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    public function index(){
        $items = Item::all();
        return response()->json($items, 200);
    }

    public function store(Request $request)
    {

            $request->validate([
                'code' => 'required|string|max:255|unique:items,code',
                'item_name' => 'required|string',
                'price' => 'required',
                'image' => 'nullable|mimes:png,jpg,jpeg|max:2048'
            ]);

        $item = Item::create([
            'code' => $request->code,
            'item_name' => $request->item_name,
            'price' => $request->price,
            'image' => $request->image
        ]);
            // $item = new Item();
            // $item->code = Str::random(6);
            // $item->name = $request->name;
            // $item->price = $request->price;

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('public/');
                $item->image = str_replace('public/', '', $path);
            }


            Log::create([
                'user_id' => auth()->id(),
                'action' => 'create_item',
                'description' => 'Item created with ID: ' . $item->id
            ]);

            return response()->json($item, 201);
        


    }

    public function show($id){
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        return response()->json($item, 200);
    
    }

    public function edit($id){
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        return response()->json($item, 200);
    }
    public function update(Request $request, $id){
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $request->validate([
            'code' => 'string|max:255|unique:items,code,'.$item->id,
            'item_name' => 'string',
            'price' => 'integer',
            'image' => 'nullable|mimes:png,jpg,jpeg|max:2048'
        ]);

        $item->update([
            'code' => $request->code,
            'item_name' => $request->item_name,
            'price' => $request->price,
            'image' => $request->image
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/');
            $item->image = str_replace('public/', '', $path);
        }

        Log::create([
            'user_id' => auth()->id(),
            'action' => 'update_item',
            'description' => 'Item updated with ID: ' . $item->id
        ]);

        return response()->json($item, 200);
    }

    public function destroy($id){
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }
        $item->delete();
        Log::create([
            'user_id' => auth()->id(),
            'action' => 'delete_item',
            'description' => 'Item deleted with ID: ' . $item->id
        ]);
        return response()->json(['message' => 'Item deleted'], 200);
    }
}
