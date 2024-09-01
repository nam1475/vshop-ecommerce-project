<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\CustomerAddress;
use App\Models\Customer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_price', 20, 2)->nullable();
            $table->string('status', 45)->nullable();
            $table->string('session_id', 255)->nullable();
            $table->foreignIdFor(CustomerAddress::class, 'customer_address_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Customer::class, 'created_by')->nullable();
            $table->foreignIdFor(Customer::class, 'updated_by')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
