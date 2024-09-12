<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Customer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('type', 45)->nullable();
            $table->string('name', 30)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('province', 50)->nullable();
            $table->string('district', 50)->nullable();
            $table->string('ward', 50)->nullable();
            $table->string('zip_code', 45)->nullable();
            $table->boolean('is_main')->default(1);
            $table->string('country_code', 3)->nullable();
            $table->foreignIdFor(Customer::class, 'customer_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
