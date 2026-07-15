<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //Tạo biến name đón dữ liệu từ faker
        $name = $this->faker->randomElement([
            'Hahaha','Huhuhu','Hihihi','Hohaho','Hehehe','Hohahaha'

        ]);
        return [
            'name'=>ucfirst($name),
            'slug'=>Str::slug($name).'-'.$this->faker->unique()->numberBetween(1,1000),
            'category_id'=> Category::inRandomOrder()->first()->id,
            'description'=>$this->faker->text(200),
            'price'=> $this->faker->numberBetween(1,100),
            'quantity'=>$this->faker->numberBetween(1,100),
            'unit'=>$this->faker->randomElement(['kg','hộp','cái','cặp']),
            'sale_price'=>$this->faker->numberBetween(1,50),
            'is_featured'=>$this->faker->boolean(),
            'is_active'=>$this->faker->boolean()
        ];
    }
}
