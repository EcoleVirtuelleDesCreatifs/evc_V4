<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certification_eligibilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('formation', 100); // clé formation concernée
            $table->enum('system_status', ['not_eligible', 'pre_eligible'])->default('not_eligible');
            $table->enum('admin_status', ['pending', 'reviewing', 'eligible', 'rejected'])->default('pending');
            $table->enum('studio_creative_status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->string('studio_creative_name')->nullable();
            $table->text('studio_creative_comment')->nullable();
            $table->unsignedBigInteger('studio_creative_validated_by')->nullable();
            $table->timestamp('studio_creative_validated_at')->nullable();
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->text('admin_comment')->nullable();
            $table->timestamp('last_evaluated_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'formation']);
            $table->index(['formation', 'system_status']);
            $table->index(['formation', 'admin_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certification_eligibilities');
    }
};
