# Project: API for Managing Companies and Employees

## Description

This project implements a REST API for managing companies and their employees. The application uses **Laravel 11**, **PHP 8.3**, **Sanctum** for authentication, and **Postman** for API testing.

The task description is available via the link
https://gist.github.com/Fanamurov/e3ac6f4ac881c157f0ea0a33501aaf5f

## Requirements

- Docker (if using Docker)
- Docker Compose (if using Docker)
- PHP 8.3+
- Composer
- MySQL

## Installation and Setup

### 1. Clone the repository

Run the following commands to clone the repository and navigate to the directory:

```bash 
git clone git@github.com:tt0y/americor-test.git
cd <your-repository-directory>
```

### 2. Environment setup

Create a `.env` file based on the example:
```bash 
cp .env.example .env
```

Configure your database connection settings in the `.env` file:

```code
DB_CONNECTION=mysql  
DB_HOST=mysql  
DB_PORT=3306  
DB_DATABASE=your_database  
DB_USERNAME=your_username  
DB_PASSWORD=your_password
```

### 3. Install dependencies

Run the following command to install the required dependencies:
```bash 
composer install
```

### 4. Generate application key

Generate the application key with the following command:
```bash 
php artisan key:generate
```

### 5. Run migrations and seeders

Run the migrations to set up the database:
```bash 
php artisan migrate
```
Seed the database with demo data:
```bash 
php artisan db:seed
```

### 6. Run the application

If using Docker, start the application with the following commands:
```bash 
docker compose up -d
```

If running locally without Docker, start the Laravel development server:
```bash 
php artisan serve
``` 

The application will be available at: `http://localhost:8000`.

## Authentication

The application uses Laravel Sanctum for authentication. You can log in as an administrator with the credentials created by the seeders:

- **Email**: `admin@admin.com`
- **Password**: `password`

After a successful login, you will receive a **Bearer Token**, which must be used for all subsequent API requests.

## Testing with Postman

### 1. Import Postman collection

In the root of the project, you will find a file named **Americor test.postman_collection.json**.

To use the collection in Postman:

1. Open Postman.
2. Click **Import** in the top left corner.
3. Select the **Americor test.postman_collection.json** file located in the root of the project.

### 2. Requests in the collection

The collection includes the following requests:

- **Login** (POST `/api/login`) – to obtain the authentication token.
- **Logout** (POST `/api/logout`) – to log out and delete the token.
- **Companies**:
    - Get list of companies (GET `/api/companies`)
    - Create a company (POST `/api/companies`)
    - Get company details (GET `/api/companies/{id}`)
    - Update a company (PUT `/api/companies/{id}`)
    - Delete a company (DELETE `/api/companies/{id}`)
- **Employees**:
    - Get list of employees (GET `/api/employees`)
    - Create an employee (POST `/api/employees`)
    - Get employee details (GET `/api/employees/{id}`)
    - Update an employee (PUT `/api/employees/{id}`)
    - Delete an employee (DELETE `/api/employees/{id}`)

### 3. Using the token

After making the **Login** request, copy the received token and add it to the `Authorization` header for all subsequent requests. The header format is:

Authorization: Bearer <your_token_here>

## Shutting down

To stop the application when using Docker:
```bash 
docker compose down
```

If running locally without Docker, stop the application by pressing `Ctrl + C` in the terminal.
