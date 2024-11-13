<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['category_id']);
            
            // Now, drop the column
            $table->dropColumn('category_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
            
            // Re-add the foreign key constraint if rolling back
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }
};
