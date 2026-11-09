# 4Xabuoya Backend

## Project Description
4Xabuoya is a food-delivery e-commerce application. This repository contains the Laravel REST API backend, which handles data management, authentication, and product operations.

## Group Information
**Group Name:** 4Xabuoya

| Member | GitHub Username |
|---|---|
| Member 1 | cake1icious |
| Member 2 | hannahpariscal |
| Member 3 | lilhoeyen |
| Member 4 | n_zela |
| Member 5 | [Add missing username] |
| Repository Owner | Xabuo |

## Technologies Used
- PHP
- Laravel 12
- MySQL / MariaDB
- Laravel Sanctum
- Composer
- Postman

## Requirements
- PHP 8.2 or higher
- Composer
- MySQL or MariaDB
- Laravel-compatible PHP extensions

## Installation and Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/Xabuo/4Xabuoya-backend.git
   ```

2. Open the project folder:

   ```bash
   cd 4Xabuoya-backend
   ```

3. Install dependencies:

   ```bash
   composer install
   ```

4. Create your environment file:

   ```bash
   copy .env.example .env
   ```

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Configure your database in the `.env` file.

7. Run migrations:

   ```bash
   php artisan migrate
   ```

8. Start the Laravel server:

   ```bash
   php artisan serve
   ```

## API Base URL

```text
http://127.0.0.1:8000/api
```

## Authentication Endpoints

| Method | Endpoint | Description |
|---|---|---|
| POST | /register | Register a customer |
| POST | /login | Customer login |
| POST | /logout | Customer logout |
| GET | /me | Get authenticated user |
| POST | /admin/login | Administrator login |
| POST | /admin/logout | Administrator logout |

Protected endpoints require a valid authentication token.

## Product Endpoints

| Method | Endpoint | Access |
|---|---|---|
| GET | /products | Public |
| GET | /products/{id} | Public |
| POST | /products | Administrator |
| PUT | /products/{id} | Administrator |
| PATCH | /products/{id} | Administrator |
| DELETE | /products/{id} | Administrator |

## User Roles and Permissions

- **Guest:** Can browse and view public products.
- **Customer:** Can register, log in, and view products.
- **Administrator:** Can manage products, including creating, updating, and deleting them.

Product write operations are protected by authentication and administrator authorization middleware.

## Testing

Use Postman to test the API endpoints.

Test the following:
- Customer registration and login.
- Customer logout.
- Public product viewing.
- Administrator login.
- Product creation, updating, and deletion.
- Unauthorized access to protected endpoints.

## Related Repository

**Frontend:** https://github.com/Xabuo/4Xabuoya-frontend

## Notes
- Configure your own local database before running the application.
- Do not upload your actual `.env` file or expose passwords and authentication tokens.
- Use `.env.example` as the environment configuration template.
