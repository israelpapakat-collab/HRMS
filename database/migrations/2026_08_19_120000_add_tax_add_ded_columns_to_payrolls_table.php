<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('before_tax_add_ded', 12, 2)->default(0)->after('gross_pay');
            $table->decimal('after_tax_add_ded', 12, 2)->default(0)->after('tax');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['before_tax_add_ded', 'after_tax_add_ded']);
        });
    }
};
