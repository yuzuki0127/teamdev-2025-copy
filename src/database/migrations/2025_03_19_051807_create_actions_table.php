<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('team_id');
            $table->string('title', 255);
            $table->text('description');
            $table->text('background');
            $table->text('purpose');
            $table->timestamp('deadline');
            $table->boolean('stopped')->default(0)->comment('停止状況 0が停止していない 1が停止');
            $table->boolean('is_completed')->default(0)->comment('完了状況 0が未完了 1が完了');
            $table->timestamps(); 
            // 外部キー制約
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
