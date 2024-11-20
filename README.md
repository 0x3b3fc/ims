# Inventory Management System

## Description
This is a simple inventory management system that allows users to add, update, delete, and view products in an inventory. The system is built using Laravel & mysql database.

## Features
- User authentication
- User roles (admin, manager, viewer)
- User management
- Add, update, delete, and view categories
- Add, update, delete, and view products
- Search products by name
- Filter products by category id
- Filter products by sku
- Add, update, delete, and view orders
- View order details
- View order history
- view product history

## Installation
1. Clone the repository
2. Run `composer install`
3. Create a `.env` file by copying the `.env.example` file
4. Run `php artisan key:generate`
5. Configure the database in the `.env` file
6. Run `php artisan migrate --seed`
7. Run `php artisan serve`

## Usage
1. Visit `http://localhost:8000` in your browser
2. Login with the default credentials:
   - Admin:
     - Email: `admin@test.com`
     - Password: `password`
   - Manager:
     - Email: `manager@test.com`
     - Password: `password`
   - viewer:
     - Email: `viewer@test.com`
     - Password: `password`

## License
this project is a test project by sief hesham as a task (Duration: 2 days)
