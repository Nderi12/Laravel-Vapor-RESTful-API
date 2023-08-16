# Deployed Project on Laravel Vapor on an AWS infrastructure
*Project URL*: https://ei5czcnukfdljefgeiqwmjgygq0utyvh.cell-1-lambda-url.us-east-1.on.aws/login
*Email*: admin@demo.com
*Password*: password

*Swagger APIs Documentation*: https://ei5czcnukfdljefgeiqwmjgygq0utyvh.cell-1-lambda-url.us-east-1.on.aws/api/documentation

The project above allows for user to: List, Get, Create, Update and Delete Users using api endpoints below

**API Endpoints**
```sh
    - `GET /users`: Returns a list of all users.
    - `GET /users/{id}`: Returns a specific user by ID.
    - `POST /users`: Creates a new user.
    - `PUT /users/{id}`: Updates an existing user by ID.
    - `DELETE /users/{id}`: Deletes a user by ID.
```


# Laravel Vapor RESTful API Project

This repository contains a RESTful API project built with Laravel and utilizes Redis for queue management to process background jobs. The application is deployed using Laravel Vapor on the AWS infrastructure. The API endpoints provided include user-related functionalities, and MySQL is used as the database to store user data.

## Installation

Follow these steps to set up and install the project locally:

1. Clone the repository from GitHub:

   ```sh
   git clone https://github.com/Nderi12/Laravel-Vapor-RESTful-API.git
   ```

2. Install the project dependencies:

   ```sh
   composer install
   ```

3. Create a new `.env` file:

   ```sh
   cp .env.example .env
   ```

4. Generate a new application key:

   ```sh
   php artisan key:generate
   ```

5. Create a new MySQL database and update the `.env` file with the database credentials:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

6. Run the database migrations:

   ```sh
   php artisan migrate
   ```

## Configure Redis for Queue Management:

In this project, we intend to send every new user an invitation. The process will be handled using laravel’s package https://github.com/predis/predis. The project is configured to use redis as our queue worker

1. Update the `.env` file to use Redis as the queue driver:

   ```dotenv
   QUEUE_CONNECTION=redis
   ```

   Start the Redis server using the command:

   ```sh
   sudo service redis-server start
   ```

2. Configure SMTP settings for sending emails:

   Open the `.env` file and update the following SMTP settings:

   ```dotenv
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host
   MAIL_PORT=your-smtp-port
   MAIL_USERNAME=your-smtp-username
   MAIL_PASSWORD=your-smtp-password
   MAIL_ENCRYPTION=ssl
   ```

3. Start the Queue Worker:

   Run the following command to start processing background jobs:

   ```sh
   php artisan queue:work
   ```

## Testing API

Run the laravel tests:

```
php artisan test
```

## Documentation

The Swagger OpenAPIs documentation is available at:

```
https://{{base_url}}/api/documentation

```

## Laravel Vapor Deployment

This project is deployed using Laravel Vapor on the AWS infrastructure. Follow these steps to deploy the application using Laravel Vapor:

1. **AWS Setup:**

   - Navigate to your AWS security credentials and get the Access Key ID and Secret Access Key.

2. **Laravel Vapor Setup:**

   - Create a team in your Laravel Vapor account.
   - Link your AWS account with Vapor by providing the Access Key ID and Secret Access Key.

3. **In the Laravel Project:**

   - Run the command `vapor login` and provide your Vapor account credentials.

4. **Initializing Vapor Project:**

   - Run the command below to configure the project settings, select your region, and install the core package (if not already installed). This will generate the `vapor.yaml` file in the root directory.
   
    ```sh
    vapor init
    ```

5. **Adding Database:**

   - Run the command `vapor database my-test-database --dev` to create a public development database.
   - Update the `vapor.yaml` database hook with the appropriate database name.

6. **Deployment:**

   - Run the command `vapor deploy specify_environment` to deploy your application to AWS Lambda and frontend assets to CloudFront (AWS CDN).
   - After a successful deploy, obtain the Environment URL and update the `.env` file's `L5_SWAGGER_CONST_HOST` variable.

7. **Accessing API Documentation:**

   - Deploy the application again using `vapor deploy specify_environment`.
   - Access the API documentation at the URL: `https://{{project_url}}/api/documentation`.

## Further Notes

- The project utilizes Laravel Vapor for efficient deployment on AWS infrastructure.
- Redis is used for queue management to process background jobs.
- MySQL is used to store user data.
- The `.env` file must be properly configured with the necessary environment-specific values.

For any additional information or support, please refer to the official Laravel and Laravel Vapor documentation.