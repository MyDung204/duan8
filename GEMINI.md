# Project Overview

This is a Laravel application built using the `laravel/livewire-starter-kit`. It leverages Laravel 12, Livewire, and Volt for a dynamic and reactive frontend experience. Authentication is handled by Laravel Fortify, and styling is managed with Tailwind CSS. The project also includes Maatwebsite/Excel for handling Excel imports/exports.

Based on the file structure, it appears to be a content management system or a blog-like application, featuring models for `Post`, `Category`, `Tag`, `Comment`, `Contact`, and `NewsletterSubscription`.

## Technologies Used

*   **Backend:** PHP 8.2+, Laravel 12
*   **Frontend:** Livewire, Volt, Alpine.js, Tailwind CSS, Vite
*   **Database:** (Likely MySQL/PostgreSQL/SQLite, configured via `.env`)
*   **Authentication:** Laravel Fortify
*   **Testing:** PestPHP
*   **Other:** Maatwebsite/Excel (for imports/exports)

## Building and Running

To set up and run the project, follow these steps:

1.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

2.  **Install JavaScript Dependencies:**
    ```bash
    npm install
    # or yarn install
    ```

3.  **Environment Setup:**
    Copy the example environment file and generate an application key:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *   **Note:** You will need to configure your database connection in the newly created `.env` file.

4.  **Run Database Migrations:**
    ```bash
    php artisan migrate
    ```

5.  **Start Development Server and Frontend Assets:**
    The `dev` script uses `concurrently` to run the Laravel development server, a queue listener, and Vite for hot-reloading frontend assets.
    ```bash
    npm run dev
    ```
    Alternatively, you can run them separately:
    ```bash
    php artisan serve
    npm run dev # for frontend assets with hot-reloading
    ```

6.  **Build Frontend Assets for Production:**
    ```bash
    npm run build
    ```

## Testing

The project uses PestPHP for testing.

To run tests:
```bash
php artisan test
# or vendor/bin/pest
```

## Development Conventions

*   **MVC Architecture:** Follows Laravel's Model-View-Controller pattern.
*   **Blade Templating:** Views are rendered using Blade templates.
*   **Livewire Components:** Interactive frontend components are built with Livewire and Volt.
*   **Styling:** Tailwind CSS is used for utility-first styling.
*   **Authentication:** Laravel Fortify provides the scaffolding for authentication features.
*   **Code Style:** Laravel Pint is used for code style enforcement (as indicated by `composer.json` dev dependencies).