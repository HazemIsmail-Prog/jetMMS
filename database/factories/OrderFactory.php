<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
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
        $customer = Customer::inRandomOrder()->first();
        return [
            'customer_id' => $customer->id,
            'phone_id' => $customer->phones()->first()->id,
            'address_id' => $customer->addresses()->first()->id,
            'created_by' => 1,
            'updated_by' => 1,
            'status_id' => 1,
            'estimated_start_date' => today(),
            'department_id' => 2,
        ];
    }
}
