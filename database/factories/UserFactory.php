<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Thien','Tu','Long'
        ]);
        return [
            'name' => ucfirst($name),
            'email' => $this->faker->unique()->safeEmail(),
            'phone'=>$this->faker->phoneNumber(),
            //Tạo biến passqwword tĩnh để lưu haassh lại, 
            //nếu  null thì hash xong lưu vào biến, còn có rồi thì cứ sử dụng 
            'password'=> self::$password ??= Hash::make('123456'),
            'status'=>$this->faker->randomElement(['active','pending']),
            'role_id'=>$this->faker->numberBetween(1,3),
            'activation_token'=>Str::random(40),//Token dùng để xác thực tài khoản
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
