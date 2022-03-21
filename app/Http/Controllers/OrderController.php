<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Orders;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class OrderController extends BaseController
{
    public function __construct() {
        $this->middleware('authorization');
    }

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

    public function findAll() {
        $order = Orders::query()
                    ->leftJoin('customers', 'customers.id','=','orders.customer_id')
                    ->leftJoin('products', 'products.id','=','orders.product_id');

        if (request()->has('q')) {
            // jika ada query "q" untuk pencarian products.title
            $q = request('q');
            $order->where("products.title", "like", "%$q%");
        }

        // data pagination
        $data = $order->paginate(10,
                    [
                        "orders.*",
                        "customers.first_name",
                        "customers.last_name",
                        "customers.address",
                        "customers.city",
                        "products.title as product_title"
                    ]);

        return $this->out(data:$data, status:"OK");
    }

    public function update(Orders $order) {
        $product = Products::find(request('product_id'));

        if ($product == NULL) {
            return $this->out(
                status:"Gagal",
                code:404,
                error:["Produk tidak ditemukan"]
            );
        }

        $order->product_id  = $product->id;
        $order->customer_id = request('customer_id');
        $order->quantity    = request('quantity');
        $order->price       = $product->price;

        $result = $order->save();

        return $this->out(
            status: $result ? "OK" : "Gagal",
            data: $result ? $order : null,
            error: $result ? null : ["Gagal merubah data"],
            code: $result ? 201 : 504
        );
    }

    public function delete(Orders $order) {
        $result = $order->delete();
        return $this->out(
            status: $result ? "OK" : "Gagal",
            data: $result ? $order : null,
            error: $result ? null : ["Gagal menghapus data"],
            code: $result ? 200 : 504
        );
    }
}
