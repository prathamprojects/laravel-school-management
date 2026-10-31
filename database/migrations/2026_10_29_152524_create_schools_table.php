<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->foreignId('state_id')->constrained()->restrictOnDelete();
            $table->foreignId('district_id')->constrained()->restrictOnDelete();
            $table->foreignId('city_id')->constrained()->restrictOnDelete();
            $table->date('establishment_date');
            $table->string('contact_number', 20);
            $table->string('login_id')->unique();
            $table->string('password');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
