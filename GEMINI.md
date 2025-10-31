# Project Overview

This is a Laravel project that appears to be a blog or content management system. It uses Livewire for building dynamic interfaces, and includes features for managing posts and categories. The application is configured for a Vietnamese audience.

## Key Technologies

*   **Backend:** PHP, Laravel
*   **Frontend:** Livewire, Tailwind CSS
*   **Database:** Not explicitly defined, but likely MySQL or another relational database supported by Laravel.

# Building and Running

## Setup

To set up the project, run the following command:

```bash
composer run setup
```

This will install composer and npm dependencies, create a `.env` file, generate an application key, and run database migrations.

## Development

To start the development server, run:

```bash
composer run dev
```

This will start the PHP development server, the queue listener, and the Vite development server.

## Testing

To run the test suite, use the following command:

```bash
composer run test
```

# Development Conventions

*   **Routing:** Routes are defined in `routes/web.php`. The project uses a combination of traditional Laravel routing and Livewire Volt for component-based routing.
*   **Models:** Models are located in the `app/Models` directory. They include logic for relationships, scopes, and attribute casting.
*   **Views:** Views are located in the `resources/views` directory. The project uses Blade templating and includes Livewire components.
*   **Styling:** The project uses Tailwind CSS for styling.
*   **Localization:** The application is configured for Vietnamese (`vi`).
