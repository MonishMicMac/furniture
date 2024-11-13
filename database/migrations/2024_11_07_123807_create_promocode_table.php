<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocode', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Unique code for the promo
            $table->date('expire_date'); // Expiration date for the promo code
            $table->boolean('action')->default(1); // Boolean for active/inactive status
            $table->enum('discount_type', ['0', '1'])->default('0'); // Enum for discount type (e.g., 0 = flat, 1 = percentage)
            $table->decimal('discount', 10, 2); // Discount value (could be percentage or flat)
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('promocode');
    }
    
};