<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->comment('姓名');
            $table->string('phone', 30)->comment('电话号码');
            $table->string('area', 20)->comment('地区');
            $table->string('shop_name')->nullable()->comment('店铺名称');
            $table->string('address')->nullable()->comment('店铺地址');
            $table->string('merchants_name', 50)->comment('招商员');
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
        Schema::dropIfExists('agent');
    }
}
