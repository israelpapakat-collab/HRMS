# Entity Relationship Diagram

```mermaid
erDiagram
    DEPARTMENTS {
        int department_id PK
        string department_name
    }

    POSITIONS {
        int position_id PK
        string position_title
    }

    EMPLOYEES {
        int employee_id PK
        int department_id FK
        int position_id FK
    }

    LEAVE_REQUESTS {
        int leave_id PK
        int employee_id FK
    }

    PAYROLLS {
        int payroll_id PK
        int employee_id FK
    }

    ATTENDANCE {
        int attendance_id PK
        int employee_id FK
    }

    WARNING_LETTERS {
        int warning_id PK
        int employee_id FK
    }

    ROLES {
        int role_id PK
        string role_name
    }

    DEPARTMENTS ||--o{ EMPLOYEES : has
    POSITIONS ||--o{ EMPLOYEES : has
    EMPLOYEES ||--o{ LEAVE_REQUESTS : submits
    EMPLOYEES ||--o{ PAYROLLS : receives
    EMPLOYEES ||--o{ ATTENDANCE : records
    EMPLOYEES ||--o{ WARNING_LETTERS : receives
    ROLES }o--o{ EMPLOYEES : assigned
```

## Relationships

| # | Relationship | Type |
|---|-------------|------|
| 1 | Department → Employees | One-to-Many |
| 2 | Position → Employees | One-to-Many |
| 3 | Employee → Leave Requests | One-to-Many |
| 4 | Employee → Payrolls | One-to-Many |
| 5 | Employee → Attendance | One-to-Many |
| 6 | Employee → Warning Letters | One-to-Many |
| 7 | Roles ↔ Employees | Many-to-Many |
