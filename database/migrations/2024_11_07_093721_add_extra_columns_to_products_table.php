<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add new columns conditionally
            if (!Schema::hasColumn('products', 'sub_category_id')) {
                $table->unsignedBigInteger('sub_category_id')->nullable();
            }

            if (!Schema::hasColumn('products', 'product_code_availability')) {
                $table->string('product_code_availability')->nullable();
            }

            if (!Schema::hasColumn('products', 'warranty_month')) {
                $table->integer('warranty_month')->nullable();
            }

            if (!Schema::hasColumn('products', 'action')) {
                $table->string('action')->nullable();
            }

            if (!Schema::hasColumn('products', 'min_order_qty')) {
                $table->integer('min_order_qty')->default(1);
            }

            if (!Schema::hasColumn('products', 'liquidation_status')) {
                $table->boolean('liquidation_status')->default(0);
            }

            // Optional: Add foreign key constraint if `sub_category_id` references a `subcategories` table
            if (!Schema::hasColumn('products', 'sub_category_id')) {
                $table->foreign('sub_category_id')->references('id')->on('subcategories')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn([
                'sub_category_id', 
                'product_code', 
                'warranty_month', 
                'action', 
                'min_order_qty', 
                'liquidation_status'
            ]);

            // Drop foreign key if exists
            $table->dropForeign(['sub_category_id']);
        });
    }
}
