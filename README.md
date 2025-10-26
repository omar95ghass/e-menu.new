# e-menu Platform

This repository contains the initial scaffold for a multilingual SaaS platform that enables restaurants to build and manage digital menus with subdomain support, plan-based permissions, and RESTful APIs.

## Features

- PHP 8+ object-oriented architecture with PDO for database access
- Public area with landing, search, and restaurant profile pages
- Restaurant dashboard stubs featuring statistics and media uploads
- Superuser panel entry point with plan management foundation
- JSON-based API endpoints for authentication, public data, restaurant management, and admin operations
- Multilingual support (Arabic/English) through language files
- Plan enforcement service to guard business rules server-side
- Modular classes for authentication, validation, file uploads, and subdomain resolution

## Getting Started

1. **Install dependencies**: ensure PHP 8+, MySQL, and required extensions (pdo_mysql, gd) are enabled.
2. **Configure database**: update environment variables `DB_HOST`, `DB_NAME`, etc., or edit `config/config.php`.
3. **Run migrations**: populate the `migrations/` directory with SQL files and apply them to create the necessary tables (users, restaurants, plans, etc.).
4. **Serve the application**: point your web server to the `public/` directory. For development you can run `php -S localhost:8000 -t public`.
5. **API access**: interact with endpoints under `/api/*` using JSON requests.

## Directory Structure

Refer to the project tree in the user requirements; the scaffold follows the exact structure for easy expansion.

## Testing

Unit and feature tests are not included in this scaffold. Add your preferred testing framework (e.g., PHPUnit, Pest) as the project evolves.
