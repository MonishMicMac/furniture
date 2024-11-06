<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('login', function (Blueprint $table) {
            $table->string('gst_no')->nullable()->after('pincode');
            $table->string('shop_name')->nullable()->after('gst_no');
            $table->string('pan_no')->nullable()->after('shop_name');
            $table->string('otp')->nullable()->after('pan_no');
            $table->enum('approval_status', ['0', '1', '2'])->default('0')->after('otp');
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
            $table->dropColumn(['gst_no', 'shop_name', 'pan_no', 'otp', 'approval_status']);
        });
    }
}
