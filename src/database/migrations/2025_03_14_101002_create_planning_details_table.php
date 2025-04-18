<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('planning_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planning_id');
            $table->string('title', 255); // 提案する施策のタイトル
            $table->text('description'); // 提案する施策の内容
            $table->text('background'); // 提案する施策の背景
            $table->text('purpose'); // 提案する施策の目的
            $table->string('cost', 255)->comment('「高」「中」「低」'); // 「高」「中」「低」
            $table->string('priority', 255)->comment('「高」「中」「低」'); // 「高」「中」「低」
            $table->text('cost_detail'); // costの理由など
            $table->text('priority_detail'); // priorityの理由など
            $table->text('process_of_reasoning'); // どういう過程でこの施策を発案したのか
            $table->timestamps();

            // 外部キー制約
            $table->foreign('planning_id')->references('id')->on('plannings');
        });
    }

    public function down()
    {
        Schema::dropIfExists('planning_details');
    }
}
