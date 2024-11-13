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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // String type for order ID
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 10, 2);
            $table->enum('approve_status', ['approved', 'pending', 'declined'])->default('pending');
            $table->string('promo_code')->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->enum('payment_mode', ['credit_card', 'debit_card', 'paypal', 'cash_on_delivery', 'other'])->default('other');
            $table->string('declined_remark')->nullable();
            $table->string('cancel_remark')->nullable();
            $table->enum('order_status', ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->dateTime('order_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->decimal('payable_amount', 10, 2);
            $table->text('feedback')->nullable();
            $table->text('delivery_address');
            $table->text('billing_address');
            $table->enum('delivery_status', ['pending', 'in_progress', 'delivered', 'cancelled'])->default('pending');
            $table->dateTime('delivery_date')->nullable();
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
    
};
