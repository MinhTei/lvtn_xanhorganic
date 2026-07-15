<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Category IDs thực tế trong DB:
     * --- Cha ---
     *  1 = Trái cây       2 = Thịt Hải sản    3 = Bơ trứng sữa
     *  4 = Rau củ         5 = Đồ hộp
     * --- Con ---
     *  6 = Trái cây nhập khẩu (cha: 1)      7 = Nước ép (cha: 1)
     *  8 = Thịt bò (cha: 2)                 9 = Thịt heo (cha: 2)
     * 10 = Thịt gà (cha: 2)                11 = Cá tươi (cha: 2)
     * 12 = Hải sản các loại (cha: 2)
     * 13 = Trứng gà (cha: 3)               14 = Trứng vịt (cha: 3)
     * 15 = Sữa các loại (cha: 3)           16 = Bơ các loại (cha: 3)
     * 17 = Rau (cha: 4)                    18 = Củ quả (cha: 4)
     * 19 = Nấm các loại (cha: 4)
     */
    public function run(): void
    {
        Product::truncate();
        ProductImage::truncate();

        $products = [
            // ===== Rau — category_id = 17 (con của Rau củ id=4) =====
            ['category_id' => 17, 'name' => 'Cải ngọt',       'slug' => 'cai-ngot',      'description' => 'Cải ngọt tươi sạch',          'price' => 12000,  'sale_price' => null,   'quantity' => 100, 'unit' => 'kg',    'is_featured' => true,  'is_active' => true],
            ['category_id' => 17, 'name' => 'Rau muống',      'slug' => 'rau-muong',     'description' => 'Rau muống tươi xanh',          'price' => 8000,   'sale_price' => null,   'quantity' => 150, 'unit' => 'bó',    'is_featured' => false, 'is_active' => true],
            ['category_id' => 17, 'name' => 'Rau mồng tơi',  'slug' => 'rau-mong-toi',  'description' => 'Rau mồng tơi tươi ngon',       'price' => 7000,   'sale_price' => null,   'quantity' => 120, 'unit' => 'bó',    'is_featured' => false, 'is_active' => true],

            // ===== Củ quả — category_id = 18 (con của Rau củ id=4) =====
            ['category_id' => 18, 'name' => 'Cà rốt',         'slug' => 'ca-rot',        'description' => 'Cà rốt tươi giàu vitamin A',  'price' => 15000,  'sale_price' => 12000,  'quantity' => 80,  'unit' => 'kg',    'is_featured' => true,  'is_active' => true],
            ['category_id' => 18, 'name' => 'Dưa leo',        'slug' => 'dua-leo',       'description' => 'Dưa leo mát lành',             'price' => 10000,  'sale_price' => null,   'quantity' => 200, 'unit' => 'kg',    'is_featured' => false, 'is_active' => true],
            ['category_id' => 18, 'name' => 'Bắp cải trắng', 'slug' => 'bap-cai-trang', 'description' => 'Bắp cải trắng tươi',           'price' => 18000,  'sale_price' => 14000,  'quantity' => 60,  'unit' => 'kg',    'is_featured' => false, 'is_active' => true],
            ['category_id' => 18, 'name' => 'Khoai tây',      'slug' => 'khoai-tay',     'description' => 'Khoai tây Đà Lạt tươi',        'price' => 20000,  'sale_price' => null,   'quantity' => 90,  'unit' => 'kg',    'is_featured' => false, 'is_active' => true],

            // ===== Nấm — category_id = 19 (con của Rau củ id=4) =====
            ['category_id' => 19, 'name' => 'Nấm hương',      'slug' => 'nam-huong',     'description' => 'Nấm hương tươi thơm ngon',     'price' => 35000,  'sale_price' => null,   'quantity' => 80,  'unit' => 'kg',    'is_featured' => false, 'is_active' => true],

            // ===== Trái cây nhập khẩu — category_id = 6 (con của Trái cây id=1) =====
            ['category_id' => 6,  'name' => 'Táo Envy đỏ',           'slug' => 'tao-envy-do',           'description' => 'Táo Envy nhập khẩu',            'price' => 120000, 'sale_price' => 99000,  'quantity' => 50,  'unit' => 'kg',    'is_featured' => true,  'is_active' => true],
            ['category_id' => 6,  'name' => 'Kiwi xanh New Zealand',  'slug' => 'kiwi-xanh-new-zealand', 'description' => 'Kiwi xanh New Zealand ngọt mát', 'price' => 85000,  'sale_price' => null,   'quantity' => 40,  'unit' => 'kg',    'is_featured' => false, 'is_active' => true],
            ['category_id' => 6,  'name' => 'Cam vàng Úc',            'slug' => 'cam-vang-uc',           'description' => 'Cam vàng Úc nhiều vitamin C',    'price' => 75000,  'sale_price' => null,   'quantity' => 60,  'unit' => 'kg',    'is_featured' => true,  'is_active' => true],

            // ===== Nước ép — category_id = 7 (con của Trái cây id=1) =====
            ['category_id' => 7,  'name' => 'Dưa hấu ruột đỏ',       'slug' => 'dua-hau-ruot-do',       'description' => 'Dưa hấu ruột đỏ ngọt mát',      'price' => 18000,  'sale_price' => 15000,  'quantity' => 150, 'unit' => 'kg',    'is_featured' => true,  'is_active' => true],
            ['category_id' => 7,  'name' => 'Sầu riêng Ri6',          'slug' => 'sau-rieng-ri6',         'description' => 'Sầu riêng Ri6 Miền Tây',        'price' => 95000,  'sale_price' => null,   'quantity' => 40,  'unit' => 'kg',    'is_featured' => true,  'is_active' => true],

            // ===== Thịt bò — category_id = 8 (con của Thịt Hải sản id=2) =====
            ['category_id' => 8,  'name' => 'Thịt bò Úc',            'slug' => 'thit-bo-uc',            'description' => 'Thịt bò Úc tươi nhập khẩu',    'price' => 350000, 'sale_price' => 299000, 'quantity' => 50,  'unit' => 'kg',    'is_featured' => true,  'is_active' => true],

            // ===== Thịt heo — category_id = 9 (con của Thịt Hải sản id=2) =====
            ['category_id' => 9,  'name' => 'Sườn non heo',          'slug' => 'suon-non-heo',          'description' => 'Sườn non heo tươi ngon',        'price' => 160000, 'sale_price' => null,   'quantity' => 60,  'unit' => 'kg',    'is_featured' => false, 'is_active' => true],
            ['category_id' => 9,  'name' => 'Thịt heo sạch',         'slug' => 'thit-heo-sach',         'description' => 'Thịt heo sạch tươi ngon',        'price' => 130000, 'sale_price' => 110000, 'quantity' => 80,  'unit' => 'kg',    'is_featured' => false, 'is_active' => true],

            // ===== Thịt gà — category_id = 10 (con của Thịt Hải sản id=2) =====
            ['category_id' => 10, 'name' => 'Ức gà phi lê',          'slug' => 'uc-ga-phi-le',          'description' => 'Ức gà phi lê sạch, ít mỡ',       'price' => 95000,  'sale_price' => 80000,  'quantity' => 100, 'unit' => 'kg',    'is_featured' => true,  'is_active' => true],

            // ===== Cá tươi — category_id = 11 (con của Thịt Hải sản id=2) =====
            ['category_id' => 11, 'name' => 'Cá hồi Na Uy',          'slug' => 'ca-hoi-na-uy',          'description' => 'Cá hồi Na Uy tươi nhập khẩu',  'price' => 450000, 'sale_price' => null,   'quantity' => 30,  'unit' => 'kg',    'is_featured' => true,  'is_active' => true],
            ['category_id' => 11, 'name' => 'Cá diêu hồng',          'slug' => 'ca-dieu-hong',          'description' => 'Cá diêu hồng tươi ngon',         'price' => 80000,  'sale_price' => 65000,  'quantity' => 50,  'unit' => 'kg',    'is_featured' => false, 'is_active' => true],

            // ===== Hải sản các loại — category_id = 12 (con của Thịt Hải sản id=2) =====
            ['category_id' => 12, 'name' => 'Tôm sú tươi',           'slug' => 'tom-su-tuoi',           'description' => 'Tôm sú tươi còn sống',           'price' => 280000, 'sale_price' => 250000, 'quantity' => 40,  'unit' => 'kg',    'is_featured' => true,  'is_active' => true],
            ['category_id' => 12, 'name' => 'Hàu sữa tươi',          'slug' => 'hau-sua-tuoi',          'description' => 'Hàu sữa tươi Vũng Tàu',          'price' => 180000, 'sale_price' => null,   'quantity' => 50,  'unit' => 'kg',    'is_featured' => false, 'is_active' => true],
            ['category_id' => 12, 'name' => 'Mực ống tươi',          'slug' => 'muc-ong-tuoi',          'description' => 'Mực ống tươi ngon',               'price' => 200000, 'sale_price' => null,   'quantity' => 40,  'unit' => 'kg',    'is_featured' => false, 'is_active' => true],

            // ===== Trứng gà — category_id = 13 (con của Bơ trứng sữa id=3) =====
            ['category_id' => 13, 'name' => 'Trứng gà sạch',         'slug' => 'trung-ga-sach',         'description' => 'Trứng gà ta sạch, an toàn',      'price' => 45000,  'sale_price' => null,   'quantity' => 200, 'unit' => 'vỉ 10', 'is_featured' => false, 'is_active' => true],

            // ===== Trứng vịt — category_id = 14 (con của Bơ trứng sữa id=3) =====
            ['category_id' => 14, 'name' => 'Trứng vịt lộn',         'slug' => 'trung-vit-lon',         'description' => 'Trứng vịt lộn thơm béo',         'price' => 8000,   'sale_price' => null,   'quantity' => 300, 'unit' => 'trứng', 'is_featured' => false, 'is_active' => true],

            // ===== Sữa các loại — category_id = 15 (con của Bơ trứng sữa id=3) =====
            ['category_id' => 15, 'name' => 'Sữa tươi hữu cơ',      'slug' => 'sua-tuoi-huu-co',       'description' => 'Sữa tươi hữu cơ 100%',           'price' => 55000,  'sale_price' => 48000,  'quantity' => 120, 'unit' => 'hộp',   'is_featured' => true,  'is_active' => true],

            // ===== Bơ các loại — category_id = 16 (con của Bơ trứng sữa id=3) =====
            ['category_id' => 16, 'name' => 'Bơ lạt Anchor',         'slug' => 'bo-lat-anchor',         'description' => 'Bơ lạt Anchor New Zealand',      'price' => 85000,  'sale_price' => null,   'quantity' => 60,  'unit' => 'hộp',   'is_featured' => false, 'is_active' => true],
        ];

        foreach ($products as $productData) {
            $slug = $productData['slug'];
            $product = Product::create($productData);

            // Tạo ảnh cho sản phẩm từ file ảnh tương ứng
            ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => asset('assets/clients/img/product/' . $slug . '.jpg'),
                'is_primary' => 1,
            ]);
        }
    }
}
