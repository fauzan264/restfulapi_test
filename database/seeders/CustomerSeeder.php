<?php

namespace Database\Seeders;

use App\Models\Customers;
use Faker\Factory as DataFaker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataFaker = DataFaker::create('id_ID');
        $data = [];
        for ($i=0; $i < 100; $i++) { 
            $gender = $dataFaker->randomElement(['male', 'female']);
            $data[] = [
                'email'         => $dataFaker->email(),
                'first_name'    => $dataFaker->firstName($gender),
                'last_name'     => $dataFaker->lastName(),
                'city'          => $dataFaker->city(),
                'address'       => $dataFaker->address(),
                'password'      => Hash::make('test123'),
            ];
        }
        (new Customers())->insert($data);
    }
}
