<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropIndex(['period_start', 'period_end']);
            $table->dropColumn(['period_start', 'period_end']);
            $table->decimal('taxable', 12, 2)->default(0)->after('gross_pay');
            $table->decimal('tax', 12, 2)->default(0)->after('taxable');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['taxable', 'tax']);
            $table->date('period_start')->after('employee_id');
            $table->date('period_end')->after('period_start');
            $table->index(['period_start', 'period_end']);
        });
    }
};