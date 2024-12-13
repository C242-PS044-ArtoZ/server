<?php

  namespace Database\Seeders;

  use App\Models\Transaction;
  use App\Models\User;
  use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

  class DatabaseSeeder extends Seeder
  {
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
      // Create test users
      User::factory(10)->create();

      // Create transactions for each user
      User::all()->each(function (User $user) {
        Transaction::factory(5)->create(['user_id' => $user->id]);
      });
    }
  }
