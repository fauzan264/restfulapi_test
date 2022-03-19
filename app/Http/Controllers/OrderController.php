<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Orders;
use Carbon\Carbon;

class OrderController extends BaseController
{
    public function store() {
        // cari data produk berdasarkan product_id
        $product = Products::find(\request('product_id'));

        // jika produk tidak ditemukan
        if($product == NULL) {
            // kembalikan nilai dengan format produk tidak ditemukan
            return $this->out(
                status  : "Gagal",
                code    : 404,
                error   : ["Produk tidak ditemukan"]
            );
        }

        $order = new Orders();
        $order->order_date  = Carbon::now('Asia/Jakarta');
        $order->product_id  = $product->id;
        $order->customer_id = request('customer_id');
        $order->quantity    = request('quantity');
        $order->price       = $product->price;

        // jika operasi insert berhasil
        if ($order->save() == true) {
            return $this->out(data: $order, status: "OK", code:201);
        } else {
            return $this->out(status: "Gagal", error: ["Order gagal disimpan"], code: 504);
        }
    }
}
