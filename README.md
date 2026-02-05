# Graduation Management System

A comprehensive Laravel-based application for managing graduation events, graduates (Wisudawan), and generating graduation books (Buku Wisuda).

## Features

### Admin Panel
- **Dashboard**: Overview of system statistics.
- **Manage Graduation Books (Buku Wisuda)**: Create and manage graduation periods/books.
- **Manage Graduates (Wisudawan)**:
  - Add, edit, and delete graduate data.
  - **Import CSV**: Bulk import graduate data.
  - local API integration for data fetching.
- **Template Management**: Manage templates for graduation book layout.
- **Archives & Generation**: Generate PDF versions of graduation books using customizable templates.

### Public Portal
- **Landing Page**: Information about graduation.
- **Alumni Search**: Publicly accessible search to find alumni data (`/cari-alumni`).
- **Digital Book**: View graduation books online.

## Technology Stack

- **Framework**: Laravel 12.x
- **Frontend**: Blade Templates, TailwindCSS v4
- **Build Tool**: Vite
- **Database**: SQLite (Default) / MySQL Compatible
- **PDF Generation**: `barryvdh/laravel-dompdf`
- **API**: Laravel Sanctum

## Installation

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd graduation
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   Copy the example environment file and configure it:
   ```bash
   cp .env.example .env
   ```
   *Modify `.env` to set your database credentials if not using SQLite.*

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Database Setup**
   If using SQLite (default), create the database file:
   ```bash
   touch database/database.sqlite
   ```
   Run migrations to set up the schema:
   ```bash
   php artisan migrate
   ```

7. **Link Storage**
   Enable public access to storage files (for images/uploads):
   ```bash
   php artisan storage:link
   ```

8. **Build Assets**
   Compile frontend assets:
   ```bash
   npm run build
   ```

## Usage

### Development Server
Start the local development server:
```bash
php artisan serve
```
Access the application at `http://localhost:8000`.

### API
The application uses a local API for fetching wisudawan data in the admin panel.
- Endpoint: `/api/wisudawan`
- Logic: `App\Http\Controllers\Api\WisudawanController`

## Directory Structure (Key Components)

- `app/Http/Controllers/Admin`: Admin management logic.
- `app/Http/Controllers/Api`: API controllers (Wisudawan data).
- `app/Models`: Eloquent models (Wisudawan, BukuWisuda, Template).
- `resources/views/admin`: Blade templates for the admin panel.
- `routes/web.php`: Web routes configuration.
- `routes/api.php`: API routes configuration.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).