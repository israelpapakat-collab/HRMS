<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->index('last_name');
            $table->index('department_id');
            $table->index('position_id');
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->index('status');
            $table->index(['employee_id', 'status']);
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['last_name']);
            $table->dropIndex(['department_id']);
            $table->dropIndex(['position_id']);
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['employee_id', 'status']);
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
