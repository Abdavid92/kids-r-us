<?php

namespace App\Listeners;

use App\Events\ProductSold;
use App\Models\SoldProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisterNewSale implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ProductSold $event
     * @return void
     */
    public function handle(ProductSold $event)
    {
        SoldProduct::query()->create([
            'sale_price' => $event->product->price,
            'product_id' => $event->product->id
        ]);
    }
}
