<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('enter_date')->comment('录单日期');
            $table->string("name", 40)->index('index_name')->comment("制单人");
            $table->string('ordersn', 100)->unique('ordersn')->comment("订单号");
            $table->string('area', 20)->comment("地区");
            $table->string('t_id')->index('tid')->comment('路线id');
            $table->string('a_id')->index('aid')->comment('代理商id');
            $table->string('vip_card')->nullable()->index('index_card')->comment("vip卡号");
            $table->decimal("tour_fee_amount", 10,2)->default(0)->comment("总团费");
            $table->decimal("deposit_amount", 10,2)->default(0)->comment("定金");
            $table->decimal("rebate_amount", 10,2)->default(0)->comment("返利金额");
            $table->decimal("balance_amount", 10,2)->default(0)->comment("尾款金额");
            $table->decimal("collection_amount", 10,2)->default(0)->comment("代收款");
            $table->string('up_group_date', 20)->comment("跟团日期");
            $table->string('off_group_date', 20)->comment("离团日期");
            $table->string('numbers', 20)->comment("人数");
            $table->string('meet_day', 20)->nullable()->comment("接站日期");
            $table->string('meet_number', 20)->nullable()->comment("接站航班或者火车号");
            $table->string('leave_day', 20)->nullable()->comment("送站日期");
            $table->string('leave_number', 20)->nullable()->comment("送站航班或者火车号");
            $table->text('remark')->nullable()->comment("备注");
            $table->integer('status')->index('index_status')->default(-1)->comment("订单状态 -2， -1 审核中，1审核通过");
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
        Schema::dropIfExists('order');
    }
}
