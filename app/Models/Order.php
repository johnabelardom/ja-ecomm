<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    public function items($seller_id = null) {
        if (intval($seller_id) > 0) {
            return $this->hasMany(OrderItem::class)->where('seller_id', intval($seller_id));
        } else 
            return $this->hasMany(OrderItem::class);
    }

    public function products() {
        return $this->items()->where('type', 'product');
    }

    public function charges() {
        return $this->items()->where('type', 'charge');
    }

    public function subtotal() {
        return $this->products()->sum(DB::raw('order_items.price * order_items.quantity'));
    }

    public function total() {
        return $this->items()->sum(DB::raw('order_items.price * order_items.quantity'));
    }

}
