<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('payrolls', 'period_start')) {
            Schema::table('payrolls', function (Blueprint $table) {
                try {
                    $table->dropIndex(['period_start', 'period_end']);
                } catch (\Throwable $e) {
                }
                $table->dropColumn(['period_start', 'period_end']);
            });
        }

        Schema::table('payrolls', function (Blueprint $table) {
            if (!Schema::hasColumn('payrolls', 'taxable')) {
                $table->decimal('taxable', 12, 2)->default(0)->after('gross_pay');
            }
            if (!Schema::hasColumn('payrolls', 'tax')) {
                $table->decimal('tax', 12, 2)->default(0)->after('taxable');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            if (Schema::hasColumn('payrolls', 'taxable')) {
                $table->dropColumn('taxable');
            }
            if (Schema::hasColumn('payrolls', 'tax')) {
                $table->dropColumn('tax');
            }
            if (!Schema::hasColumn('payrolls', 'period_start')) {
                $table->date('period_start')->after('employee_id');
                $table->date('period_end')->after('period_start');
                $table->index(['period_start', 'period_end']);
            }
        });
    }
};