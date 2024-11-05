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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Name of the product
            $table->text('description')->nullable();  // Description of the product
            $table->decimal('price', 10, 2);  // Price of the product
            $table->integer('quantity')->default(0);  // Quantity of the product
            $table->string('product_image_path')->nullable(); // Path for product image
            $table->enum('action', [0, 1])->default(0);  // Action flag for soft delete
            $table->string('brand')->nullable(); 
            $table->decimal('discount_price', 10, 2)->nullable(); // Discount price
            $table->integer('reviews_count')->default(0); // Number of reviews
            $table->decimal('average_rating', 2, 1)->default(0); // Average rating
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
        Schema::dropIfExists('products');
    }
};
