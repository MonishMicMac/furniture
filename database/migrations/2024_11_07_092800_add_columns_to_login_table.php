<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login', function (Blueprint $table) {
            // Adding new columns
            $table->string('billing_address')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('password')->nullable();  // Storing hashed password
            $table->string('profile_img_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('login', function (Blueprint $table) {
            // Drop the added columns
            $table->dropColumn(['billing_address', 'delivery_address', 'password', 'profile_img_path']);
        });
    }
}
