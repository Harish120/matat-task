# Order Management System API

## Description

This is an API for an Order Management System built using Laravel 11. It provides endpoints for user authentication, order management, and synchronization of orders. The API uses Laravel Passport for authentication and follows RESTful principles.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
    - [Authentication](#authentication)
    - [Orders](#orders)
- [Contributing](#contributing)
- [License](#license)

## Requirements

- PHP >= 8.1
- Composer
- Laravel >= 11
- MySQL or any other supported database
- Laravel Passport

## Installation

1. Clone the repository:

   ```bash
   git clone git@github.com:Harish120/matat-task.git
   cd matat-task/backend
   ```

2. Install the dependencies:

   ```bash
   composer install
   ```

3. Set up your environment file:

   ```bash
   cp .env.example .env
   ```

4. Generate the application key:

   ```bash
   php artisan key:generate
   ```

5. Set up your database in the `.env` file:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

6. Run the migrations:

   ```bash
   php artisan migrate
   ```

7. Install Laravel Passport:

   ```bash
   php artisan passport:install
   ```

8. Start the server:

   ```bash
   php artisan serve
   ```

## Usage

You can use tools like Postman or curl to interact with the API. Make sure to include the `Authorization` header with the token received upon login for authenticated routes.

## API Endpoints

### Authentication

- **Login**
    - **URL**: `/api/login`
    - **Method**: `POST`
    - **Body**:
      ```json
      {
        "email": "user@example.com",
        "password": "your_password"
      }
      ```

- **Register**
    - **URL**: `/api/register`
    - **Method**: `POST`
    - **Body**:
      ```json
      {
        "name": "User Name",
        "email": "user@example.com",
        "password": "your_password"
      }
      ```

- **Logout**
    - **URL**: `/api/logout`
    - **Method**: `POST`
    - **Headers**:
        - `Authorization: Bearer {access_token}`

- **Get Current User**
    - **URL**: `/api/user`
    - **Method**: `GET`
    - **Headers**:
        - `Authorization: Bearer {access_token}`

### Orders

- **Fetch Orders**
    - **URL**: `/api/orders`
    - **Method**: `GET`
    - **Query Parameters**:
        - `page`: Page number for pagination (optional)
        - `per_page`: Number of items per page (optional)
        - `filter`: Search filter (optional)
    - **Headers**:
        - `Authorization: Bearer {access_token}`

- **Sync Orders**
    - **URL**: `/api/orders/sync`
    - **Method**: `POST`
    - **Headers**:
        - `Authorization: Bearer {access_token}`

## Contributing

Contributions are welcome! Please open an issue or submit a pull request for any changes you'd like to make.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
