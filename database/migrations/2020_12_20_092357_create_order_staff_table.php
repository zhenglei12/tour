<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_staff', function (Blueprint $table) {
            $table->id();
            $table->string('name', '50')->comment('姓名');
            $table->integer('order_id')->index('index_order_id')->comment('订单id');
            $table->string('id_crad', '50')->comment('证件号码');
            $table->string('phone', 20)->comment('联系电话');
            $table->string('type', 50)->nullable()->comment('类型');
            $table->string('card_type', 20)->nullable()->comment('证件');
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
        Schema::dropIfExists('order_staff');
    }
}
