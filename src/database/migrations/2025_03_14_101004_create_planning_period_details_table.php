<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningPeriodDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('planning_period_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planning_period_id');
            $table->string('planning_detail_title', 255); // 提案するタスクのタイトル
            $table->string('planning_detail_period', 255); // 施策を実施する期間 (週単位)
            $table->string('planning_detail_manager', 255); // 担当する人
            $table->timestamps();

            // 外部キー制約
            $table->foreign('planning_period_id')->references('id')->on('planning_periods');
        });
    }

    public function down()
    {
        Schema::dropIfExists('planning_period_details');
    }
}
