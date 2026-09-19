<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('gender', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('marital_status', 50)->nullable();
            $table->foreignId('department_id')->constrained('departments', 'department_id');
            $table->foreignId('position_id')->constrained('positions', 'position_id');
            $table->decimal('annual_salary', 12, 2)->nullable();
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->string('office_location', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->integer('annual_leave_balance')->default(0);
            $table->integer('sick_leave_balance')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
