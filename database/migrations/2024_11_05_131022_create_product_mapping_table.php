<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductMappingTable extends Migration
{
    public function up()
    {
        Schema::create('product_mapping', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Foreign key to products table
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Foreign key to categories table
            $table->enum('action', [0, 1])->default(0);
            $table->timestamps(); // Optional, for created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_mapping');
    }
}
