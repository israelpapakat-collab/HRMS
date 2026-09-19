<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('rate_per_hour', 12, 2)->default(0)->after('gross_pay');
            $table->integer('dependents')->default(0)->after('rate_per_hour');
            $table->dropColumn('notes');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['rate_per_hour', 'dependents']);
            $table->text('notes')->nullable()->after('processed_at');
        });
    }
};