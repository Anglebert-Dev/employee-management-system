<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'names' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'employee_identifier' => 'EMP-'.$this->faker->unique()->numberBetween(1, 100000),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}

