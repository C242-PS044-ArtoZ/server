<?php

  namespace Database\Factories;

  use App\Models\Transaction;
  use App\Models\User;
  use Illuminate\Database\Eloquent\Factories\Factory;

  /**
   * @extends Factory<Transaction>
   */
  class TransactionFactory extends Factory
  {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
        'nominal' => fake()->randomFloat(2, 10, 1000),
        'type' => fake()->randomElement(['income', 'expense']),
        'description' => fake()->sentence(),
        'user_id' => User::factory(), // Automatically associate with a user
      ];
    }
  }
