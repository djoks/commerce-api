# Laravel E-commerce API

This Laravel application provides a robust backend for an e-commerce platform, featuring user authentication, product management, order processing, and more. Built with Laravel 10, it leverages several packages to enhance functionality, including real-time websockets, state management, and automated API documentation.

## Features

-   User registration, verification, login, and management
-   Product categorization and management
-   Order creation, tracking, and management
-   Payment processing and status updates
-   Supplier management
-   Discount and promotions management
-   Real-time event broadcasting via websockets
-   Automated API documentation with Scramble

## Dependencies

-   PHP ^8.1
-   Laravel Framework ^10.8
-   Laravel Sanctum for API token authentication
-   BeyondCode Laravel WebSockets for real-time broadcasting
-   Spatie packages for roles & permissions, activity logging, and media library

## Setup and Configuration

1. **Clone the repository:**

    ```bash
    https://github.com/djoks/commerce-api.git
    ```

2. **Navigate to the project directory:**

    ```bash
    cd commerce-api
    ```

3. **Install Composer dependencies:**

    ```bash
    composer install
    ```

4. **Copy the .env.example file to .env and configure your environment variables:**

    ```bash
    cp .env.example .env
    ```

    Don't forget to set your database and mail server settings.

5. **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

6. **Run migrations and seeders (if any):**

    ```bash
    php artisan migrate --seed
    ```

7. **Serve the application:**

    ```bash
    php artisan serve
    ```

8. **Access the application via:**

    ```bash
    http://localhost:8000
    ```

## Accessing API Documentation

This application uses Scramble to automatically generate and serve API documentation. To view the API documentation:

1. Ensure Scramble is correctly set up as per its documentation.
2. Access the Scramble UI through:
   `bash
http://localhost:8000/docs/api
`

## Rate Limiting

API routes are rate-limited to ensure fair resource usage. Adjust the rate limits in the Kernel.php file as per your requirements.

## Contributing

Contributions are welcome! Please feel free to submit pull requests or open issues to discuss potential improvements or fixes.

## License

This Laravel e-commerce API is open-sourced software licensed under the MIT license.
