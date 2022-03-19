<?php

namespace App\Http\Controllers;
use App\Models\Customers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    public function auth() {
        $authHeader = \request()->header('Authorization'); // basic xxxbase64encodexxx
        $keyAuth    = substr($authHeader, 6); // hilangkan text basic
        
        $plainAuth = base64_decode($keyAuth); // decode text info lagin
        $tokenAuth = explode(':', $plainAuth); // pisahkan email:password

        $email  = $tokenAuth[0]; // email
        $pass   = $tokenAuth[1]; // password

        $data   = (new Customers())->newQuery()
                                ->where(['email' => $email])
                                ->get([
                                    'id',
                                    'first_name',
                                    'last_name',
                                    'email',
                                    'password'
                                ])->first();

        if($data == null) {
            // jika data customer tidak ditemukan
            return $this->out(
                status  : 'Gagal',
                code    : 404, // tidak ditemukan
                error   : ['Pengguna tidak ditemukan'],
            );
        } else {
            // jika data customer ditemukan

            // melakukan pencocokan password yang telah dienkripsi
            if (Hash::check($pass, $data->password)) {
                // jika password tidak cocok
                $data->token = hash('sha256', Str::random(10)); //membuat token untuk dikirim ke client
                unset($data->password); // hilangkan informasi password yang akan dikirim ke client
                $data->update(); // update token disimpan ke table customer

                return $this->out(
                    data: $data,
                    status : 'OK'
                );
            } else {
                // jika password cocok
                return $this->out(
                    status: 'Gagal',
                    code: 401, // 401 unauthorized
                    error: ["Anda tidak memiliki wewenang"],
                );
            }

        }
    }
}
