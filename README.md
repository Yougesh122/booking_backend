Laravel & Next.js Project – Setup and Run Instructions

This document provides step-by-step instructions to set up the database, configure environment variables,

run the Laravel backend, run the Next.js frontend, and test the integration between both applications.

1. Database Setup Instructions
Create the following two MySQL databases before running the application:
• main_booking_app_db
• analytics_db

3. Laravel Backend Setup Instructions

2.1 Environment Variables (.env)
Update your Laravel .env file with the following configuration:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=main_booking_app_db
DB_USERNAME=root
DB_PASSWORD=
ANALYTICS_DB_DATABASE=analytics_db
ANALYTICS_DB_USERNAME=root
ANALYTICS_DB_PASSWORD=
SESSION_DRIVER=file

2.2 Install Dependencies
Run the following command:
composer update

2.3 Run Database Migrations
Execute the migration command to create tables:
php artisan migrate

2.4 Start Laravel Server
Start the Laravel development server:
php artisan serve

2.5 Available API Routes
GET http://127.0.0.1:8000/api/v1/bookings
GET http://127.0.0.1:8000/api/v1/get-bookings-status
POST http://127.0.0.1:8000/api/v1/bookings
GET http://127.0.0.1:8000/api/v1/bookings/{id}
PUT http://127.0.0.1:8000/api/v1/bookings/{id}
DELETE http://127.0.0.1:8000/api/v1/bookings/{id}
