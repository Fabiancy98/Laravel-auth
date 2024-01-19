# Your Laravel App Name

Welcome to the documentation for running [Your Laravel App Name] using Laravel Sail. This guide will help you set up and run the Laravel application on your local machine.

## Prerequisites

Before you start, ensure that you have the following installed on your system:

-   [Docker](https://www.docker.com/)
-   [Docker Compose](https://docs.docker.com/compose/)
-   [Git](https://git-scm.com/)

## Installation

1. Clone the repository to your local machine:

    ```bash
    git clone https://github.com/fabiancy98/moses-app.git
    ```

2. Navigate to the project directory:

    ```bash
    cd moses-app
    ```

3. Copy the example environment file:

    ```bash
    cp .env.example .env
    ```

4. Install PHP dependencies using Laravel Sail:

    ```bash
    ./vendor/bin/sail composer install
    ```

5. Install JavaScript dependencies:

    ```bash
    ./vendor/bin/sail npm install
    ```

6. Generate an application key:

    ```bash
    ./vendor/bin/sail artisan key:generate
    ```

7. Configure your database connection in the `.env` file:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=sail
    DB_USERNAME=sail
    DB_PASSWORD=password
    ```

    Note: The database configuration might differ based on your Laravel Sail setup.

8. Migrate the database:

    ```bash
    ./vendor/bin/sail artisan migrate
    ```

9. Seed the database (if needed):

    ```bash
    ./vendor/bin/sail artisan db:seed
    ```

## Run the Application

Now that you have set up the Laravel application with Sail, you can run it using the following command:

    ```bash
    ./vendor/bin/sail up
    ```
