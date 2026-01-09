<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_task_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->index();
            $table->unsignedBigInteger('task_type_id')->index();
            $table->unsignedInteger('quantity')->default(1);
            $table->dateTime('performed_at')->index();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('task_type_id')->references('id')->on('admin_task_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_task_logs');
    }
};
