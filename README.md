#  Multi-Tenant Application

A robust, multi-tenant Laravel application that supports separate databases, user authentication, and role-based permissions for different tenants. Additionally, tenants can manage posts, upload images, and define custom rules based on the user's location.

## Prerequisites

Before you begin, ensure you have the following installed on your machine:

-   **PHP (>=8.0)**
-   **MySQL (>=8.0)**
-   **Composer** (for PHP dependencies)

## Technologies Used

-   **Laravel Framework (v11)**: PHP framework for building scalable and maintainable backends.
-   **Laravel Sanctum (v4)**: For handling JWT-based authentication.
-   **stancl/tenancy (v3.8)**: Multi-tenancy package for separating tenant data across databases and subdomains.
-   **MySQL**: Database engine for handling tenant-specific data.

## Features

### Multi-Tenancy

-   Supports separating data per tenant via subdomains or dedicated databases.
-   Tenant-specific database schemas to ensure complete isolation of data.

### Authentication

-   Allows user registration and login under specific tenants.
-   Admin roles can be assigned to users with specific permissions for managing tenant resources.

### Post Management

-   Tenants can create, edit, and delete posts.
-   Image uploads for posts are stored in tenant-specific folders.

### Rules

-   Admins can create specific messages or promotions based on user locations (e.g., show custom messages to users from specific countries).

## Installation

### Step 1: Clone the Repository

```bash
git clone https://github.com/hasanrabiee/backendBrink.git
```

### Step 2: Install Dependencies

Use Composer to install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies (if applicable):

```bash
npm install
# or if you are using Yarn
yarn install
```

### Step 3: Environment Setup

Create a copy of `.env` configuration file:

```bash
cp .env.example .env
```

Configure your `.env` file with the necessary details such as database connection, mail settings, and tenancy configuration. Here’s an example `.env` setup:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tenant_master_db
DB_USERNAME=root
DB_PASSWORD=

TENANCY_DATABASES_CREATE=true
TENANCY_DATABASES_PREFIX=tenant_
```

### Step 4: Set Up Database

Create your database(s) and run the following migration command to set up the tables:

```bash
php artisan migrate
```

### Step 6: Run the Application

After configuring everything, you can start the development server:

```bash
php artisan serve
```

Or, if you are using Docker:

```bash
docker-compose up
```

Your application will now be running at `http://localhost:8000`.

## Usage

### Multi-Tenant

-   Tenants are isolated by subdomains or databases, ensuring data separation.
-   Create tenants with specific databases or subdomains for each.

### Authentication

-   Users can register and log in within their respective tenants.
-   Admins have special roles for managing tenant resources.

### Post Management

-   Admins can create, edit, and delete posts, which are tenant-specific.
-   Images uploaded for posts are stored in each tenant's dedicated folder under `storage/tenant*`.

### Rules

-   Admins can create custom messages or promotions based on the user's location (using r profile information).

## API Documentation

Here are some important API endpoints:

-   **POST** `/login`: User login for a tenant.
-   **POST** `/register`: User registration for a tenant.
-   **GET** `/posts`: Get all posts for the tenant.
-   **POST** `/posts`: Create a new post.
