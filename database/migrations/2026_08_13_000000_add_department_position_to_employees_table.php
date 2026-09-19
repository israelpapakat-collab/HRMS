<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('employees', function ($table) {
                $table->string('department_name', 255)->nullable();
                $table->string('position_title', 255)->nullable();
            });
            Schema::table('employees', function ($table) {
                $table->dropColumn(['department_id', 'position_id']);
            });

            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement(<<<'SQL'
            CREATE TABLE "employees_new" (
                "employee_id" integer primary key autoincrement not null,
                "user_id" integer,
                "first_name" varchar not null,
                "middle_name" varchar,
                "last_name" varchar not null,
                "gender" varchar,
                "date_of_birth" date,
                "marital_status" varchar,
                "annual_salary" numeric,
                "hourly_rate" numeric,
                "office_location" varchar,
                "start_date" date,
                "annual_leave_balance" integer not null default '0',
                "sick_leave_balance" integer not null default '0',
                "department_name" varchar,
                "position_title" varchar,
                foreign key("user_id") references "users"("id") on delete set null
            )
            SQL);

        DB::statement(<<<'SQL'
            INSERT INTO "employees_new" (
                "employee_id", "user_id", "first_name", "middle_name", "last_name",
                "gender", "date_of_birth", "marital_status", "annual_salary", "hourly_rate",
                "office_location", "start_date", "annual_leave_balance", "sick_leave_balance",
                "department_name", "position_title"
            )
            SELECT
                e."employee_id", e."user_id", e."first_name", e."middle_name", e."last_name",
                e."gender", e."date_of_birth", e."marital_status", e."annual_salary", e."hourly_rate",
                e."office_location", e."start_date", e."annual_leave_balance", e."sick_leave_balance",
                d."department_name", p."position_title"
            FROM "employees" e
            LEFT JOIN "departments" d ON d."department_id" = e."department_id"
            LEFT JOIN "positions" p ON p."position_id" = e."position_id"
            SQL);

        DB::statement('DROP TABLE "employees"');
        DB::statement('ALTER TABLE "employees_new" RENAME TO "employees"');
        DB::statement('CREATE INDEX "employees_last_name_index" ON "employees" ("last_name")');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('employees', function ($table) {
                $table->foreignId('department_id')->nullable()->constrained('departments', 'department_id');
                $table->foreignId('position_id')->nullable()->constrained('positions', 'position_id');
            });
            Schema::table('employees', function ($table) {
                $table->dropColumn(['department_name', 'position_title']);
            });

            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement(<<<'SQL'
            CREATE TABLE "employees_new" (
                "employee_id" integer primary key autoincrement not null,
                "user_id" integer,
                "first_name" varchar not null,
                "middle_name" varchar,
                "last_name" varchar not null,
                "gender" varchar,
                "date_of_birth" date,
                "marital_status" varchar,
                "department_id" integer not null,
                "position_id" integer not null,
                "annual_salary" numeric,
                "hourly_rate" numeric,
                "office_location" varchar,
                "start_date" date,
                "annual_leave_balance" integer not null default '0',
                "sick_leave_balance" integer not null default '0',
                foreign key("user_id") references "users"("id") on delete set null
            )
            SQL);

        DB::statement(<<<'SQL'
            INSERT INTO "employees_new" (
                "employee_id", "user_id", "first_name", "middle_name", "last_name",
                "gender", "date_of_birth", "marital_status", "annual_salary", "hourly_rate",
                "office_location", "start_date", "annual_leave_balance", "sick_leave_balance"
            )
            SELECT
                "employee_id", "user_id", "first_name", "middle_name", "last_name",
                "gender", "date_of_birth", "marital_status", "annual_salary", "hourly_rate",
                "office_location", "start_date", "annual_leave_balance", "sick_leave_balance"
            FROM "employees"
            SQL);

        DB::statement('DROP TABLE "employees"');
        DB::statement('ALTER TABLE "employees_new" RENAME TO "employees"');
        DB::statement('CREATE INDEX "employees_last_name_index" ON "employees" ("last_name")');

        DB::statement('PRAGMA foreign_keys = ON');
    }
};