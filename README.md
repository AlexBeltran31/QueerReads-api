📚 QueerReads API

QueerReads API is a RESTful API built with Laravel 12.

It provides user authentication using OAuth2 (Laravel Passport), role-based access control, and advanced business logic beyond a simple CRUD system.

This project was developed using Test-Driven Development (TDD) principles.
---

🏗 Tech Stack

-Laravel 12

-Laravel Passport (OAuth2 authentication)

-PHPUnit (TDD)

-SQLite (for testing)

-MySQL (development/production)

-Git Flow workflow

-Scribe (automatic API documentation)
---

🔐 Authentication

Authentication is implemented using Laravel Passport, which provides an OAuth2 server.

After login, users receive an access token that must be included in protected requests:

    Authorization: Bearer {access_token}

All protected routes require a valid token.
---

👥 Roles & Authorization

The application includes two roles:

    * user

    * admin

Authorization rules:

-Admin users can manage books, categories and users.

-Users can manage their own reading list and reviews.

-Only admins can delete any review.

-Users can delete only their own reviews.

Role-based access is enforced using middleware.
---

📌 Project Structure

    * Public controllers separated from Admin controllers

    * Role middleware for authorization

    * Many-to-many relationships with pivot data

    * Database constraints to enforce integrity

    * Clean architecture following REST principles
---

📦 Resources Implemented

    👤 Users
        * Register
        * Login
        * Logout
        * View own profile
        * Update own profile
        * Dele own account
        * Admin: list users
        * Admin: delete any user

    📚 Books
        * Public list of books
        * Public view single book
        * Admin: create book
        * Admin: update book
        * Admin: delete book

    🏷 Categories
        * Public list of categories
        * Public books by categories
        * Admin: create category
        * Admin: update category
        * Admin: delete category
        * Many-to-many relationship with books

    📖 Reading List
    Authenticated users can:
        * Add book to reading list
        * Update reading status (to_read, reading, finished)
        * Remove book from reading list
        * Get a random book with status to_read

    ⭐ Reviews
        * Users can create a review only if the book status is finished
        * Only one review per user per book
        * Reviews are publicly accessible
        * Users can delete their own reviews
        * Admins can delete any review
        * Unique database constraint prevents duplicate reviews
---

🎯 Business Logic Highlights

This project goes beyond basic CRUD operations by implementing:

-Status-based review creation (must finish book first)

-Unique review enforcement

-Ownership-based authorization

-Role-based route protection

-Random book suggestion from reading list

-Database-level constraints for integrity
---

🧪 Testing

The project was developed using Test-Driven Development (TDD).

Feature tests cover:

    * Authentication

    * Authorization

    * Business rules

    * Validation

    * Edge cases

    * Role restrictions

Run all tests:

    php artisan test
---

🚀 Installation

    * git clone https://github.com/AlexBeltran31/QueerReads-api.git

    * cd queerreads-api

    * composer install

    * cp .env.example .env


        Edit the .env file and configure your database credentials:

            DB_DATABASE=queerreads

            DB_USERNAME=root

            DB_PASSWORD=
        
        ⚠️ Make sure the database exists before running migrations.


    * php artisan key:generate

    * php artisan migrate

    * php artisan passport:install

    * php artisan db:seed

    * php artisan serve
---

📚 API Documentation

The API documentation was automatically generated using the **Scribe** package.

To generate documentation:

    php artisan screibe:generate

After stating the server, access the documentation at:

    http://127.0.0.1:8000/docs
---

🔄 API Example Flow

1. Register or login to receive an access token
2. Use the token in the Authorization header
3. Access protected endpoints such as:
    - Reading List
    - Reviews
    - Random book suggestion
---

💜 Author

Developed by Alex Beltrán

Academic project – Backend Development with Laravel