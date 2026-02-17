<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('base_url');
            $table->string('endpoint_path')->nullable();
            $table->string('auth_type')->default('none');
            $table->string('username')->nullable();
            $table->text('password')->nullable();
            $table->text('token')->nullable();
            $table->string('sender_id')->nullable();
            $table->string('request_type')->default('json');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_gateways');
    }
};
