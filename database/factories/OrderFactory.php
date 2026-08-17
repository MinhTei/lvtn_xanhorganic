<?php

namespace Database\Factories;

use App\Models\Orders;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Orders>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement(['Đơn hàng 1', 'Đơn hàng 2']);

        return [
            'user_id'=>User::inRandomOrder()->first()->id,
            'order_code'=>$this->faker->unique()->postCode(),
            'subtotal'=>$this->faker->randomFloat(2,100,1000),
            'discount_amount'=>$this->faker->randomFloat(2,0,100),
            'shipping_fee'=>$this->faker->randomFloat(2,10,50),
            'shipping_name'=>User::inRandomOrder()->first()->name,
            'shipping_phone'=>$this->faker->phoneNumber(),
            'shipping_address'=>$this->faker->address(),
            'total_amount'=>$this->faker->randomFloat(2,100,1000),
            'status'=>$this->faker->randomElement(['pending','processing','completed','cancelled']),
            'note'=>$this->faker->text(10),
            
        ];
    }
}
