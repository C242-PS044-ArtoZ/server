<?php

  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  return new class extends Migration {
    public function up(): void
    {
      Schema::create('transactions', function (Blueprint $table) {
        $table->ulid('id')->primary();
        $table->decimal('nominal', 15, 2);
        $table->enum('type', ['income', 'expense']);
        $table->string('description')->nullable();
        $table->foreignUlid('user_id')->constrained('users')->onDelete('cascade');
        $table->timestamps();
        $table->softDeletes();
      });
    }

    public function down(): void
    {
      Schema::dropIfExists('transactions');
    }
  };
