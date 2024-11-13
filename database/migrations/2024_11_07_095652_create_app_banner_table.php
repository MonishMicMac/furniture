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
        Schema::create('app_banner', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID column
            $table->string('img_path'); // Column for image path
            $table->string('type'); // Column for type
            $table->string('action'); // Column for action (e.g., link or action associated with the banner)
            $table->timestamps(); // created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_banner');
    }
};
