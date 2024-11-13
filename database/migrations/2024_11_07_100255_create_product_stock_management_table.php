<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStockManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_stock_management', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Foreign key to products table
            $table->string('product_code')->unique();
            $table->integer('open_balance')->default(0);
            $table->integer('dispatch')->default(0);
            $table->integer('total_stock')->default(0);
            $table->integer('sales')->default(0);
            $table->integer('balance_stock')->default(0);
            $table->integer('closing_stock')->default(0);
            $table->integer('other_sale')->default(0);
            $table->integer('canceled_stock')->default(0);
            $table->integer('decline_stock')->default(0);
            $table->integer('current_stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_stock_management');
    }
}

