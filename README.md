# Task Assignment Management Portal (TaskFlow)

A full-featured **Task Assignment & Workflow Management Portal** built with **Laravel 12** and **Blade Templating Engine**. Designed to provide dual-role access for both **Administrators** and **Team Members (Employees)**, including comprehensive CRUD capabilities, form validation messages, advanced record listings, search/filtering, and real-time status updates.

---

## 🌟 Key Features

### 1. Dual-Portal Architecture ("Admin Dono Taraf Ka Aaye")
- **Admin Portal**:
  - **Overview Dashboard**: Live metric counters (Total Tasks, Pending, In Progress, Completed, Overdue, Total Members), priority distribution, team workload progress bars, and recent activity feed.
  - **Task Management (Full CRUD)**:
    - **Create**: Detailed assignment form with title, instructions, assignee dropdown, priority (Urgent, High, Medium, Low), status, due date, and kickoff note.
    - **Read (Listing & Filtering)**: Record directory with real-time keyword search, status filter, priority filter, member filter, overdue alert filter, and sorting.
    - **View / Details**: Task card with timeline, comments history, assignee details, and overdue warning badges.
    - **Update**: Edit all task parameters and log changes automatically to the activity history.
    - **Delete**: Safe delete with confirmation modal dialog.
  - **Team Member Management (User CRUD)**:
    - View all members with their active/completed task counters.
    - Add new members with role assignment (`admin` or `member`) and department.
    - Edit member details and credentials.
    - Remove members with automatic task unassignment.
- **Team Member / Employee Portal**:
  - **Personal Dashboard**: Personalized metrics ("My Assigned", "My Pending", "My In Progress", "My Completed", "My Overdue").
  - **My Tasks Directory**: Filter and search through only tasks assigned to the logged-in user.
  - **Task View & Quick Progress Update**: Member can view assignment requirements, change task status (e.g. *Pending* &rarr; *In Progress* &rarr; *Completed*), and append completion remarks/notes.
  - **Activity & Discussion**: Member can post comments or ask questions directly on the task timeline.

### 2. Forms & Validation Messages
- **Server-Side Validation**: Rigorous validation on all inputs (required fields, string lengths, unique email checks, valid dates).
- **Inline Field Feedback**: `@error('field') <div class="invalid-feedback">{{ $message }}</div> @enderror` displays error messages directly beneath each input field with red styling.
- **Flash Alerts**: Global dismissible bootstrap alerts for `success`, `error`, and `info` actions.
- **CSRF Protection**: All forms utilize `@csrf` tokens.

### 3. Testing & Demo Convenience
- **1-Click Quick Demo Login**: The login screen includes instant 1-click shortcut buttons to log in as Admin or any team member without typing credentials.

---

## 🔑 Demo Login Accounts

| Role | Name | Email | Password | Department |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Admin Manager | `admin@taskflow.local` | `password123` | Management & Operations |
| **Member** | Sarah Khan | `sarah@taskflow.local` | `password123` | UI/UX & Frontend |
| **Member** | Rahul Sharma | `rahul@taskflow.local` | `password123` | Backend Engineering |
| **Member** | Priya Patel | `priya@taskflow.local` | `password123` | Quality Assurance |
| **Member** | Aman Verma | `aman@taskflow.local` | `password123` | DevOps & Cloud |

---

## 🚀 How to Run the Project

### Option A: Double-Click Batch File (Windows)
Double-click [`start-server.bat`](file:///c:/Users/chhan/Downloads/task%20assignment/start-server.bat) in the project root. It will start the server automatically.

### Option B: Terminal Command
1. Open PowerShell / Command Prompt in the project directory:
   ```powershell
   $env:PATH = "C:\Users\chhan\php;" + $env:PATH
   php artisan serve
   ```
2. Open your web browser and navigate to:
   ```
   http://127.0.0.1:8000
   ```

---

## 🧪 Automated Tests

The application includes automated feature tests covering authentication, access control, CRUD operations, and validation rules:
```powershell
$env:PATH = "C:\Users\chhan\php;" + $env:PATH
php artisan test --filter=TaskPortalTest
```
*(All 8 test suites and 42 assertions pass with 100% success).*

---

## 📁 Project Architecture & Key Files

- **Models**:
  - [`app/Models/User.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Models/User.php) - User model with role methods (`isAdmin()`, `isMember()`) and task relationships.
  - [`app/Models/Task.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Models/Task.php) - Task entity with status/priority badge helpers and overdue calculation.
  - [`app/Models/TaskComment.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Models/TaskComment.php) - Task activity log & discussion comments.
- **Controllers**:
  - [`app/Http/Controllers/AuthController.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Http/Controllers/AuthController.php) - Login, Register, 1-Click Quick Login, and Logout.
  - [`app/Http/Controllers/Admin/DashboardController.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Http/Controllers/Admin/DashboardController.php) - Administrative dashboard statistics.
  - [`app/Http/Controllers/Admin/TaskController.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Http/Controllers/Admin/TaskController.php) - Full Task CRUD, search, filter, and pagination.
  - [`app/Http/Controllers/Admin/MemberController.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Http/Controllers/Admin/MemberController.php) - User management CRUD.
  - [`app/Http/Controllers/Member/DashboardController.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Http/Controllers/Member/DashboardController.php) - Member personal metrics.
  - [`app/Http/Controllers/Member/TaskController.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Http/Controllers/Member/TaskController.php) - Member task list, details, and status update.
- **Middleware**:
  - [`app/Http/Middleware/EnsureAdmin.php`](file:///c:/Users/chhan/Downloads/task%20assignment/app/Http/Middleware/EnsureAdmin.php) - Prevents unauthorized members from accessing admin actions.
- **Blade Views**:
  - Layouts: [`layouts/app.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/layouts/app.blade.php), [`layouts/guest.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/layouts/guest.blade.php)
  - Auth: [`auth/login.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/auth/login.blade.php), [`auth/register.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/auth/register.blade.php)
  - Admin:
    - [`admin/dashboard.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/admin/dashboard.blade.php)
    - [`admin/tasks/index.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/admin/tasks/index.blade.php)
    - [`admin/tasks/create.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/admin/tasks/create.blade.php)
    - [`admin/tasks/edit.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/admin/tasks/edit.blade.php)
    - [`admin/tasks/show.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/admin/tasks/show.blade.php)
    - [`admin/members/index.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/admin/members/index.blade.php)
    - [`admin/members/create.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/admin/members/create.blade.php)
    - [`admin/members/edit.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/admin/members/edit.blade.php)
  - Member:
    - [`member/dashboard.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/member/dashboard.blade.php)
    - [`member/tasks/index.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/member/tasks/index.blade.php)
    - [`member/tasks/show.blade.php`](file:///c:/Users/chhan/Downloads/task%20assignment/resources/views/member/tasks/show.blade.php)
