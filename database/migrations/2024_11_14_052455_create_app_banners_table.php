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
    Schema::create('app_banners', function (Blueprint $table) {
        $table->id();
        $table->string('img_path'); // To store the image path
        $table->string('type'); // To define the banner type (e.g., 'promo', 'advertisement')
        $table->enum('action', ['0', '1']); // Action column (0 for inactive, 1 for active)
        $table->timestamps(); // To store created_at and updated_at dates
    });
}

public function down()
{
    Schema::dropIfExists('app_banners');
}

};
