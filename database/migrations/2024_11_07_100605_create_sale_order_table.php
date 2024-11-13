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
        Schema::create('sale_order', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // String type for order ID
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_code');
            $table->integer('quantity')->unsigned();
            $table->decimal('per_qty_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sale_order');
    }
    
};
