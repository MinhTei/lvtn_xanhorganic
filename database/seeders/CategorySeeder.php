<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::truncate(); //Xóa id resset lại 
        $categories = [
            [
                'name' => 'Trái cây',
                'slug' => 'trai-cay',
                'is_active' => true,
                'children' => [
                    ['name' => 'Trái cây nhập khẩu', 'slug' => 'trai-cay-nhap-khau', 'is_active' => true],
                    ['name' => 'Nước ép', 'slug' => 'nuoc-ep', 'is_active' => true],
                    
                ]
            ],

            [
                'name' => 'Thịt Hải sản',
                'slug' => 'thit-hai-san',
                'is_active' => true,
                'children' => [
                    ['name' => 'Thịt bò', 'slug' => 'thit-bo', 'is_active' => true],
                    ['name' => 'Thịt heo', 'slug' => 'thit-heo', 'is_active' => true],
                    ['name' => 'Thịt gà', 'slug' => 'thit-ga', 'is_active' => true],
                    ['name' => 'Cá tươi', 'slug' => 'ca-tuoi', 'is_active' => true],
                    ['name' => 'Hải sản các loại', 'slug' => 'hai-san-cac-loai', 'is_active' => true],
                    
                ]
            ],

            [
                'name' => 'Bơ trứng sữa',
                'slug' => 'bo-trung-sua',
                'is_active' => true,
                'children' => [
                    ['name' => 'Trứng gà', 'slug' => 'trung-ga', 'is_active' => true],
                    ['name' => 'Trứng vịt', 'slug' => 'trung-vit', 'is_active' => true],
                    ['name' => 'Sữa các loại ', 'slug' => 'sua-cac-loai', 'is_active' => true],
                    ['name' => 'Bơ các loại', 'slug' => 'bo-cac-loai', 'is_active' => true],
                ]
            ],
            [
                'name' => 'Rau củ',
                'slug' => 'rau-cu',
                'is_active' => true,
                'children' => [
                    ['name' => 'Rau ', 'slug' => 'rau-an-la', 'is_active' => true],
                    ['name' => 'Củ quả', 'slug' => 'cu-qua', 'is_active' => true],
                    ['name' => 'Nấm các loại', 'slug' => 'nam-cac-loai', 'is_active' => true],
                ]
            ],

            [
                'name' => 'Đồ hộp',
                'slug' => 'do-hop',
                'is_active' => true,
            ]

        ];

        $flag = [];
        foreach ($categories as $category) {
            $children = $category['children'] ?? [];
            unset($category['children']);
            $category['parent_id'] = null;
            $parent = Category::create($category);

            foreach ($children as $child) {
                $child['parent_id'] = $parent->id;
                $flag[] = $child;
            }
        }
        foreach ($flag as $child) {
            Category::create($child);
        }
    }
}
