<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vaccinations', function (Blueprint $table) {
            if (!Schema::hasColumn('vaccinations', 'actual_date')) {
                $table->date('actual_date')->nullable()->after('scheduled_date');
            }
            if (!Schema::hasColumn('vaccinations', 'notes')) {
                $table->text('notes')->nullable()->after('actual_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vaccinations', function (Blueprint $table) {
            if (Schema::hasColumn('vaccinations', 'actual_date')) {
                $table->dropColumn('actual_date');
            }
            if (Schema::hasColumn('vaccinations', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
