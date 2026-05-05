<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('milestones')) {
            Schema::create('milestones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('child_id')->constrained()->onDelete('cascade');
                $table->enum('category', ['motorik', 'kognitif', 'sosial', 'bicara']);
                $table->string('milestone_name');
                $table->text('description')->nullable();
                $table->boolean('is_achieved')->default(false);
                $table->date('achieved_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
