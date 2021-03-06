<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_info', function (Blueprint $table) {
            $table->id();
            $table->integer('t_id')->index('index_tid')->comment('行程id');
            $table->string("meal")->default(1)->comment("用餐 1自理，2含早，3含中，4含晚，5早中，6早晚，7中晚，8早中晚");
            $table->string("stay")->default(1)->comment("住宿1 自理，2行程安排");
            $table->text("content")->default(null)->comment("内容");
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
        Schema::dropIfExists('trip_info');
    }
}
