# DBL Site Application Documentation

## 1. Application Overview

DBL Site is a Laravel-based human resources and payroll management application. It provides an authenticated web interface for managing employees and HR operations, plus an authenticated API for selected employee, department, leave, and payroll workflows.

The application supports:

- Employee records and employment details
- Departments and positions
- Attendance records
- Leave requests and approval/rejection
- Payroll records, deductions, taxes, and net pay
- Employee evaluations
- Warning letters
- PDF reports
- Activity logs
- User profiles, roles, and permissions
- Application settings

The main web interface is implemented with Laravel Blade templates. Tailwind CSS supplies styling, Alpine.js supplies lightweight browser interactions, and Vite builds frontend assets.

## 2. Technology Stack

| Area | Technology |
| --- | --- |
| Backend framework | Laravel 12 |
| Language/runtime | PHP 8.2 or newer |
| Database | MySQL by default; Laravel supports other configured drivers |
| ORM | Laravel Eloquent |
| Authentication | Laravel authentication and Breeze controllers/routes |
| Authorization | Application roles plus the `permission` middleware |
| Templates | Blade |
| Frontend build | Vite |
| CSS | Tailwind CSS and PostCSS |
| JavaScript | Alpine.js and Axios |
| PDF generation | `barryvdh/laravel-dompdf` |
| Testing | PHPUnit 11 through Laravel's test runner |
| Web server deployment | Nginx with PHP-FPM |

## 3. Application Architecture

The request flow is the standard Laravel MVC pattern:

1. A browser or API client sends a request to the Laravel front controller in `public/index.php`.
2. Laravel loads routes from `routes/web.php` or `routes/api.php`.
3. Authentication and authorization middleware protect the requested action.
4. A controller validates input, queries Eloquent models, and applies business rules.
5. The web controllers return Blade views or PDF downloads. API controllers return JSON resources/responses.
6. Eloquent persists data through the migration-defined database schema.
7. Models using `LogsActivity` create activity log entries for tracked changes.

Important directories:

| Directory | Responsibility |
| --- | --- |
| `app/Http/Controllers` | Web, API, and authentication request handlers |
| `app/Http/Requests` | Form request validation classes |
| `app/Http/Resources` | API response resources |
| `app/Http/Middleware` | Request middleware, including permission checks |
| `app/Models` | Eloquent models and relationships |
| `app/Traits` | Reusable model behavior such as activity logging |
| `database/migrations` | Database schema history |
| `database/seeders` | Roles and development users |
| `resources/views` | Blade pages, layouts, forms, and report templates |
| `resources/css` | Tailwind/PostCSS entry styles |
| `resources/js` | Vite JavaScript entry points |
| `routes` | Web, API, authentication, and console route definitions |
| `public` | Public web root and compiled assets |
| `deploy` | Nginx configuration and Ubuntu deployment script |
| `tests` | Feature and unit tests |

## 4. Functional Modules

### Dashboard

`DashboardController` builds the authenticated dashboard. It provides a summary view over core HR activity and statistics.

Route: `GET /dashboard`

### Employee Management

Employees can be listed, searched, created, edited, and deleted. An employee stores personal details, employment dates, salary information, leave balances, department, position, and office location.

Web routes:

- `GET /employees`
- `GET /employees/create`
- `POST /employees`
- `GET /employees/{employee}/edit`
- `PUT /employees/{employee}`
- `DELETE /employees/{employee}`

API routes are available for listing, creating, viewing, updating, and deleting employees.

### Departments and Positions

Departments and positions provide organizational reference data used by employee records. Both have web CRUD screens and API endpoints for departments.

Web routes:

- `GET|POST /departments`
- `GET /departments/create`
- `GET|PUT|DELETE /departments/{department}` and its edit route
- `GET|POST /positions`
- `GET /positions/create`
- `GET|PUT|DELETE /positions/{position}` and its edit route

### Attendance

Attendance records are associated with employees and can be listed, created, edited, and deleted through the web interface.

Routes:

- `GET /attendance`
- `GET /attendance/create`
- `POST /attendance`
- `GET /attendance/{attendanceRecord}/edit`
- `PUT /attendance/{attendanceRecord}`
- `DELETE /attendance/{attendanceRecord}`

### Leave Management

Users can submit leave requests. Authorized users can approve or reject requests. The reports module can export approved leave data as a PDF.

Web routes:

- `GET /leaves`
- `GET /leaves/create`
- `POST /leaves`
- `POST /leaves/{leaveRequest}/approve`
- `POST /leaves/{leaveRequest}/reject`

API routes provide list, create, view, update, and delete operations. API updates require the `approve-leave` permission.

### Payroll

Payroll records contain salary, gross pay, hourly rate, dependents, before-tax and after-tax adjustments, taxable income, tax, deductions, net pay, processing status, processor, and processing timestamp. Payroll deductions are linked to payroll and deduction definitions.

Web routes:

- `GET /payrolls`
- `GET /payrolls/create`
- `POST /payrolls`
- `GET /payrolls/{payroll}`
- `GET|PUT|DELETE /payrolls/{payroll}` and its edit route

API listing and creation are available under `/api/payrolls` and require the `view-payroll` permission.

### Evaluations

Evaluations record an employee review and the user who performed it. Users can list, create, and view evaluations.

Routes:

- `GET /evaluations`
- `GET /evaluations/create`
- `POST /evaluations`
- `GET /evaluations/{evaluation}`

### Warning Letters

Warning letters are issued to employees and associated with the issuing user. They can be listed, created, edited, updated, and deleted from the web interface.

Routes:

- `GET /warning-letters`
- `GET /warning-letters/create`
- `POST /warning-letters`
- `GET /warning-letters/{warningLetter}/edit`
- `PUT /warning-letters/{warningLetter}`
- `DELETE /warning-letters/{warningLetter}`

### Reports

The reports area provides browser views and PDF downloads for:

- Employees: `/reports/employees`
- Departments: `/reports/departments`
- Approved leaves: `/reports/leaves`

Append `?export=pdf` to a report URL to download the corresponding PDF generated from the report Blade template.

### Activity Logs

`LogController` exposes the activity log listing at `GET /logs`. Activity logging is provided by the `LogsActivity` trait used by selected models, and log entries can reference the acting user and a polymorphic subject.

### Settings and Profile

Settings are available at `GET /settings` and updated with `POST /settings`. Authenticated users can manage their profile at `/profile`, including profile updates and account deletion.

## 5. Routes and Access Control

### Web routes

Most application routes are grouped under both `auth` and `verified` middleware. Profile routes require authentication but are not in the verified-only group. Authentication routes are loaded from `routes/auth.php`.

### API routes

API routes are defined in `routes/api.php` and require authentication. Department and employee mutations use `create-employee` or `update-employee`. Leave updates use `approve-leave`. Payroll list/create operations use `view-payroll`.

The `permission` middleware is registered in `bootstrap/app.php` and implemented by `app/Http/Middleware/CheckPermission.php`. It returns:

- HTTP 401 when no authenticated user is available
- HTTP 403 when the user lacks the requested permission

### Roles and permissions

The seeded role codes and permissions are:

| Role | Permissions |
| --- | --- |
| `admin` | `create-employee`, `update-employee`, `approve-leave`, `view-payroll` |
| `hr` | `create-employee`, `update-employee`, `approve-leave`, `view-payroll` |
| `supervisor` | `approve-leave` |
| `employee` | None defined in `User::hasPermission` |

Administrators bypass permission checks in `CheckPermission`. Permissions are currently defined in application code rather than in a separate permissions table.

## 6. Data Model

The principal Eloquent models are:

- `User`: authenticated account, role assignments, processed payrolls, evaluations, and issued warning letters
- `Role`: role definitions connected to users through `role_user`
- `Employee`: personal and employment record with related HR history
- `Department`: organizational department with positions and employees
- `Position`: job position associated with a department and employees
- `LeaveRequest`: employee leave submission and approval state
- `AttendanceRecord`: employee attendance entry
- `Payroll`: employee payroll calculation/result and processor
- `Deduction`: reusable deduction definition
- `PayrollDeduction`: deduction applied to a payroll record
- `Evaluation`: employee evaluation and evaluator
- `WarningLetter`: disciplinary warning and issuing user
- `ActivityLog`: audit-style record of application activity

Core relationships include:

- Department has many positions and employees.
- Position belongs to a department and has many employees.
- Employee belongs to a user and has many leave requests, payrolls, attendance records, evaluations, and warning letters.
- Payroll belongs to an employee and processing user, and has many payroll deductions.
- Payroll deduction belongs to a payroll and deduction definition.
- Evaluation belongs to an employee and evaluator.
- Warning letter belongs to an employee and issuer.
- Users and roles are many-to-many through `role_user`.

The migration history is the authoritative schema source. `ERD.md` is a useful overview but should be checked against current migrations and model relationships when the schema changes.

## 7. Local Installation

### Prerequisites

Install:

- PHP 8.2 or newer with required Laravel extensions
- Composer
- Node.js and npm
- MySQL, or another database supported by the configured Laravel connection

### Standard setup

From the repository root:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

Update `.env` before migrating if the database connection is not the default configured environment. The supplied `.env.example` uses:

```dotenv
APP_URL=http://localhost
DB_CONNECTION=mysql
FILESYSTEM_DISK=local
MAIL_MAILER=log
```

Create the database and configure `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` for the local MySQL installation.

### Seed development data

```powershell
php artisan db:seed
```

The current `DatabaseSeeder` creates these development accounts, each with the password `password`:

| Email | Role |
| --- | --- |
| `admin@example.com` | Admin |
| `hr@example.com` | HR Manager |
| `supervisor@example.com` | Supervisor |
| `employee@example.com` | Employee |

These credentials are for local development only and must not be used in a production environment.

## 8. Running the Application

For a complete local development environment, use the Composer script:

```powershell
composer run dev
```

This starts:

- Laravel's development server
- The queue listener
- The Vite development server

Alternatively, run the processes separately:

```powershell
php artisan serve
npm run dev
php artisan queue:listen --tries=1
```

The application is normally available at `http://localhost:8000` when using `php artisan serve`. Vite serves frontend assets during development.

For a production-style asset build:

```powershell
npm run build
```

## 9. Testing and Quality Checks

Run the application test suite with:

```powershell
composer run test
```

The script clears configuration and runs `php artisan test`. PHPUnit configuration is stored in `phpunit.xml`.

Useful Laravel maintenance commands include:

```powershell
php artisan route:list
php artisan migrate:status
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

Run the Laravel code formatter when appropriate:

```powershell
vendor/bin/pint
```

## 10. Production Deployment

The repository includes a deployment example in `deploy/deploy.sh` for Ubuntu, Nginx, MySQL, PHP 8.3-FPM, and Composer. The script performs the following high-level operations:

1. Installs server packages.
2. Creates a MySQL database and database user.
3. Clones the application into `/var/www/html/dblsite`.
4. Installs PHP and Node dependencies.
5. Generates the Laravel key and runs migrations/seeding.
6. Caches configuration, routes, and views.
7. Installs the Nginx site configuration.
8. Provides optional Certbot and backup examples.

Before using the script in production:

- Replace the repository URL and all placeholder domain/database credentials.
- Set a strong database password and production `APP_KEY`.
- Configure `APP_ENV=production` and `APP_DEBUG=false`.
- Review whether production data should be seeded; the default seeder creates known demo accounts.
- Configure mail, queue, cache, filesystem, and session drivers for the production environment.
- Ensure `storage` and `bootstrap/cache` are writable by the web process.
- Run the Nginx configuration with the real domain and TLS certificate paths.
- Set up scheduled database backups and log rotation.

The Nginx document root must be the Laravel `public` directory, not the repository root.

## 11. Configuration and Operations

Environment configuration is loaded from `.env` and should never be committed with secrets. Important configuration areas include:

- Application URL and environment
- Encryption key
- Database connection
- Session, cache, and queue drivers
- Filesystem disk
- Mail transport and sender
- Vite application name

For deployments, use Laravel cache commands only after environment values are correct:

```powershell
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

After changing environment configuration, clear and rebuild the relevant caches.

## 12. Known Implementation Notes

- `README.md` remains the standard Laravel skeleton documentation; this file documents the DBL Site application.
- The migration directory contains the original Laravel tables, DBL Site tables, and later alteration migrations. Do not remove old migrations from an environment that already depends on their history.
- The deployment script contains placeholder values such as `your-repo-url`, `your-domain.com`, and `your-strong-password`; these must be replaced before use.
- Seeded passwords are intentionally simple for development and must be changed or removed before production.
- Permission definitions currently live in `app/Models/User.php`; adding a new permission requires updating the role-to-permission map and the relevant middleware usage.
- The current API is authenticated, but its authentication mechanism follows the project configuration and should be verified before exposing the API outside a trusted environment.

## 13. Suggested Maintenance Workflow

When adding or changing a module:

1. Add or update the migration.
2. Update the Eloquent model and relationships.
3. Add request validation in `app/Http/Requests` where appropriate.
4. Implement the web/API controller action.
5. Add or update routes and permission middleware.
6. Add Blade views or API resources.
7. Add focused feature/unit tests.
8. Update this document and `ERD.md` when the user-visible behavior or data model changes.
9. Run migrations, tests, and the frontend build before deployment.

## 14. Repository Reference

- Application entry point: `public/index.php`
- Laravel configuration bootstrap: `bootstrap/app.php`
- Web routes: `routes/web.php`
- API routes: `routes/api.php`
- Authentication routes: `routes/auth.php`
- Database schema: `database/migrations`
- Seed data: `database/seeders`
- Blade templates: `resources/views`
- Frontend entry points: `resources/js/app.js` and `resources/css/app.css`
- Deployment assets: `deploy/deploy.sh` and `deploy/nginx.conf`
