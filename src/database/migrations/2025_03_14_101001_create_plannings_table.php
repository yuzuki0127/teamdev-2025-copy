<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningsTable extends Migration
{
    public function up()
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id'); // 所属するチーム
            $table->unsignedBigInteger('survey_id'); // アンケートのID
            $table->string('planning_name', 255); // 提案する施策全体について
            $table->string('field', 255); // 提案する施策全体の概要
            $table->timestamps();

            // 外部キー制約
            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('survey_id')->references('id')->on('surveys');
        });
    }

    public function down()
    {
        Schema::dropIfExists('plannings');
    }
}
