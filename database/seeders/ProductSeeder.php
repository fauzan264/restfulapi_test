<?php

namespace Database\Seeders;

use App\Models\Products;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as DataFaker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataFaker = DataFaker::create('id_ID');

        $categories = ["Pakaian", "Gadget", "Digital"];
        $titles = [
            "Pakaian"   => [
                "material"  => ["Kaos", "Kemeja", "Celana", "Hoodie", "Jas", "Jaket"],
                "jenis"     => ["Besar", "Kecil", "Anak", "Laki-laki", "Perempuan"],
                "warna"     => ["putih", "merah", "hijau", "biru", "kuning", "pink", "ungu", "hitam"]
            ],
            "Gadget"    => [
                "material"  => ["Hp", "Table", "Laptop", "PC", "Mini PC"],
                "jenis"     => ["Samsung", "Asus", "Xiaomi", "Dell", "Acer", "Polytron"],
                "warna"     => ["Silver", "Gold", "Putih", "Hitam"]
            ],
            "Digital"   => [
                "material"  => ["Pulsa", "Kuota", "Perdana"],
                "jenis"     => ["Telkomsel", "Tri", "Axis", "XL", "Indosat Ooredoo", "Smartfren"],
                "warna"     => ["100", "50", "20", "10", "5"]
            ]
        ];

        for ($i=0; $i < 100 ; $i++) { 
            $category = $dataFaker->randomElement($categories);
            $titleStr = $dataFaker->randomElement($titles[$category]['material']);
            $titleStr .= " ". $dataFaker->randomElement($titles[$category]['jenis']);
            $titleStr .= " ". $dataFaker->randomElement($titles[$category]['warna']);

            $data [] = [
                "category"      => $category,
                "title"         => $titleStr,
                "price"         => $dataFaker->numberBetween(1, 100) * 1000,
                "descriptions"   => $dataFaker->text(),
                "stock"         => $dataFaker->numberBetween(1, 200),
                "free_shipping" => $dataFaker->numberBetween(0,1),
                "rate"          => $dataFaker->randomFloat(2,1,5)
            ];
        }
        (new Products())->insert($data);
    }
}
