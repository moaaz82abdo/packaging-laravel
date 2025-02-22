<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order)
    {
        Log::create([
            'user_id' => $order->user_id,
            'action' => 'order_created',
            'description' => "New order #{$order->id} created"
        ]);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        Log::create([
            'user_id' => $order->user_id,
            'action' => 'order_updated',
            'description' => "Order #{$order->id} updated"
        ]);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        Log::create([
            'user_id' => $order->user_id,
            'action' => 'order_deleted',
            'description' => "Order #{$order->id} deleted"
        ]);
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
