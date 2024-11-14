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
        Schema::dropIfExists('promocode');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('promocode', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->date('expire_date');
            $table->boolean('action')->default(1);
            $table->enum('discount_type', ['0', '1'])->default('0');
            $table->decimal('discount', 10, 2);
            // Add other columns that you initially had
            $table->timestamps();
        });
    }
};
