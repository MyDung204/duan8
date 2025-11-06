# Project Overview

This is a web application built with the Laravel framework, using the TALL stack:

*   **T**ailwind CSS: A utility-first CSS framework for rapid UI development.
*   **A**lpine.js: A rugged, minimal framework for composing JavaScript behavior in your markup.
*   **L**ivewire: A full-stack framework for Laravel that makes building dynamic interfaces simple, without leaving the comfort of PHP. This project uses **Volt**, which allows for single-file Livewire components.
*   **L**aravel: A web application framework with expressive, elegant syntax.

Authentication is handled by Laravel Fortify. The frontend is compiled using Vite.

## Key Features

*   User authentication (Login, Registration, Password Reset, Two-Factor Authentication).
*   Admin dashboard for managing Users, Categories, Posts, Comments, and Contacts.
*   Public-facing blog with posts, categories, and comments.
*   Contact form.
*   Search functionality for posts.

# Building and Running

1.  **Install Dependencies:**
    ```bash
    composer install
    npm install
    ```

2.  **Setup Environment:**
    Copy the `.env.example` file to `.env` and configure your database and other services.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3.  **Run Database Migrations:**
    ```bash
    php artisan migrate
    ```

4.  **Run Development Servers:**
    This command will concurrently start the PHP development server, the queue listener, and the Vite development server.
    ```bash
    composer run dev
    ```
    Alternatively, you can run them in separate terminals:
    *   `php artisan serve`
    *   `npm run dev`
    *   `php artisan queue:listen`

# Development Conventions

## Testing

The project uses Pest for testing. To run the test suite:

```bash
composer test
```
or
```bash
./vendor/bin/pest
```

## Code Style

This project uses Laravel Pint for code styling. To format your code:

```bash
vendor/bin/pint
```

The CI pipeline will also check for code style issues.
