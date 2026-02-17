<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('customer_username');
            $table->string('phone');
            $table->foreignId('template_id')->constrained('sms_templates')->cascadeOnDelete();
            $table->date('scheduled_for');
            $table->date('expiration_date');
            $table->string('status')->default('pending');
            $table->unsignedInteger('attempt_count')->default(0);
            $table->text('last_error')->nullable();
            $table->string('provider_message_id')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['customer_username', 'template_id', 'expiration_date'], 'sms_jobs_unique');
            $table->index(['status', 'scheduled_for']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_jobs');
    }
};
