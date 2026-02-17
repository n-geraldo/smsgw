<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('sms_templates')->cascadeOnDelete();
            $table->unsignedTinyInteger('run_hour');
            $table->string('timezone')->default('UTC');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_schedules');
    }
};
