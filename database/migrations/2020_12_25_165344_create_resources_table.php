<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('nickname', 50)->nullable()->comment('买家昵称');
            $table->string('name', 100)->index('index_name')->comment('姓名');
            $table->string('phone', 30)->nullable()->comment('电话号码');
            $table->string('address')->nullable()->comment('收件地址');
            $table->string('send_info')->nullable()->comment('发货信息');
            $table->string('man_name')->nullable()->index('index_name')->comment('业务员名称');
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
        Schema::dropIfExists('resources');
    }
}
