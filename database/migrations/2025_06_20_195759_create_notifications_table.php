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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Laravel notifications ka default `id` UUID hota hai
            $table->string('type'); // Notification class ka naam

            $table->morphs('notifiable'); // notifiable_id & notifiable_type — for user, admin, etc.

            $table->text('data'); // actual notification data as JSON
            $table->timestamp('read_at')->nullable(); // read/unread status
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
