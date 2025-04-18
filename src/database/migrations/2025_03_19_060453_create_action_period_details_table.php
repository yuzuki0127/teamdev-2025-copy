<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('action_period_details', function (Blueprint $table) {
            $table->id(); // PK
            $table->foreignId('action_period_id');
            $table->string('action_detail_title', 255);
            $table->timestamp('action_detail_start_date'); // 必須
            $table->timestamp('action_detail_end_date');   // 必須
            $table->string('action_detail_manager', 255); // 管理者名
            $table->timestamps(); // created_at, updated_at

            // 外部キー制約
            $table->foreign('action_period_id')->references('id')->on('action_periods')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_period_details');
    }
};

