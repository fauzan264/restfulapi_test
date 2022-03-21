<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Customers;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token      = $request->header('token');
        $customer   = Customers::where('token', $token)->first();

        // jika customer tidak ada atau token kosong
        if ($customer == null || $token == "") {
            // stop proses dan kirimkan response token invalid
            return response()->json([
                    "status"    => "Invalid",
                    "data"      => null,
                    "error"     => ["Token invalid, unauthorized!"]
                ], 401 // status 401 = unauthorized 
            );
        }
        
        // simpan data customer
        $request->setUserResolver(function() use ($customer) {
            return $customer;
        });

        // lanjutkan proses berikutnya
        return $next($request);
    }
}
