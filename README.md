# PHP_Laravel12_Custom_Authentication_Using_AngularJS

A simple custom authentication system built using **Laravel 12** as a REST API backend and **AngularJS (1.x)** as the frontend.

This project demonstrates **User Registration and Login** with separate pages and **Confirm Password validation**, without using Laravel Sanctum, Breeze, Jetstream, or JWT. It is designed for beginners who want to clearly understand how custom authentication works.

---

## Project Overview

This project covers:

* Custom user registration and login
* Password and confirm password validation
* API-based authentication
* AngularJS frontend with form handling
* Laravel 12 backend with validation and hashing
* MySQL database integration

---

## Features

* Custom authentication without Breeze, Jetstream, or Sanctum
* Separate Register and Login pages
* Password and Confirm Password validation
* AngularJS 1.8 frontend
* Laravel 12 REST API backend
* MySQL database
* Beginner-friendly folder structure
* Clean and easy-to-understand code

---

## Tech Stack

| Technology    | Usage                       |
| ------------- | --------------------------- |
| PHP           | Backend language            |
| Laravel 12    | REST API development        |
| AngularJS 1.8 | Frontend framework          |
| MySQL         | Database                    |
| REST API      | Client–server communication |

---

## Project Structure

```
laravel-angular-auth/
│
├── app/
│   ├── Http/Controllers/Api/AuthController.php
│   └── Models/User.php
│
├── routes/
│   └── api.php
│
├── public/
│   ├── register.html
│   ├── login.html
│   └── js/
│       └── app.js
│
├── database/
│   └── migrations/
│       └── create_users_table.php
│
├── .env
└── README.md
```

---

## Installation and Setup

### Step 1: Create Laravel Project

```bash
composer create-project laravel/laravel laravel-angular-auth
cd laravel-angular-auth
```

---

### Step 2: Configure Database

Create a MySQL database:

```
laravel_angular_auth
```

Update `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_angular_auth
DB_USERNAME=root
DB_PASSWORD=
```

---

### Step 3: Run Migration

```bash
php artisan migrate
```

---

## Authentication APIs

### Register API

**Endpoint**

```
POST /api/register
```

**Request Body**

```json
{
  "name": "Mihir",
  "email": "mihir@example.com",
  "password": "123456",
  "password_confirmation": "123456"
}
```

---

### Login API

**Endpoint**

```
POST /api/login
```

**Request Body**

```json
{
  "email": "mihir@example.com",
  "password": "123456"
}
```

---

## Laravel Validation Used

```php
'password' => 'required|min:6|confirmed'
```

This validation:

* Automatically checks `password_confirmation`
* Prevents mismatched passwords
* Is secure and recommended by Laravel

---

## Frontend Pages

| Page     | URL                                                                        |
| -------- | -------------------------------------------------------------------------- |
| Register | [http://127.0.0.1:8000/register.html](http://127.0.0.1:8000/register.html) |
| Login    | [http://127.0.0.1:8000/login.html](http://127.0.0.1:8000/login.html)       |

---

## Run the Project

Start the Laravel development server:

```bash
php artisan serve
```

Open in browser:

```
http://127.0.0.1:8000/register.html
```
---
## Screenshot
### Register Page
<img width="937" height="871" alt="image" src="https://github.com/user-attachments/assets/a3046218-c0ba-4637-96c7-2f5cf23cde51" />

### Login Page
<img width="765" height="722" alt="image" src="https://github.com/user-attachments/assets/d3477504-ac22-43c7-ab70-68bf8465d90c" />
---
---

## Usage Flow

1. Open the Register page
2. Enter name, email, password, and confirm password
3. Submit the form to register the user
4. Open the Login page
5. Login using registered email and password
6. Authentication is validated via API

---

## Security Practices

* Passwords are hashed using Laravel `Hash::make()`
* Validation prevents empty or weak passwords
* API-based authentication structure
* CSRF protection can be added if needed

---

## Troubleshooting

If you face issues:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

Check:

* Database credentials in `.env`
* API routes in `routes/api.php`
* AngularJS script loading correctly

---

## Ideal For

* MCA or BCA projects
* Beginners learning Laravel authentication
* Understanding API-based login systems
* Interview preparation
* Laravel + AngularJS practice

---

## License

This project is open-source and available under the MIT License.

---

## Final Note

This project focuses on clarity and simplicity. It avoids advanced authentication packages so beginners can understand how authentication works internally using Laravel and AngularJS.

You are free to extend it with sessions, roles, or token-based authentication in the future.
