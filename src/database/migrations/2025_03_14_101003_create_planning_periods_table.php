<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningPeriodsTable extends Migration
{
    public function up()
    {
        Schema::create('planning_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planning_detail_id');
            $table->string('planning_title', 255); // 提案する1期間のタスクのタイトル
            $table->string('planning_period', 255); // これをどれくらいの期間で実施するのか(月単位)
            $table->timestamps();

            // 外部キー制約
            $table->foreign('planning_detail_id')->references('id')->on('planning_details');
        });
    }

    public function down()
    {
        Schema::dropIfExists('planning_periods');
    }
}
