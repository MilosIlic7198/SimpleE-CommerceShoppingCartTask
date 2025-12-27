# Instructions on how to run the project

### Prerequisites

Ensure you have the following installed:

-   **PHP** v8.5.0
-   **Composer** v2.9.2
-   **Node.js** v25.0.0
-   **MariaDB** v12.1.2
-   **Laravel** v12.44.0
-   **Vue.js** v3.5.25

_Note: These versions are recommended but not mandatory; other versions will probably work as well._

### Database Configuration

Create a `.env` file with the following database connection variables:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=simple_ecommerce
DB_USERNAME=root
DB_PASSWORD=root
```

You can copy from the `.env.example` file using:

```bash
cp .env.example .env

### Mailtrap Configuration

Create a `.env` file with the following mailtrap configuration variables:

```ini
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username-on-mailtrap
MAIL_PASSWORD=your-password-on-mailtrap
MAIL_ENCRYPTION=tls
```

You can copy from the `.env.example` file using:
```bash
cp .env.example .env
```
```

### Also in `config/mail.php`

Change this:

`'default' => env('MAIL_MAILER', 'log'),`

to this:

`'default' => env('MAIL_MAILER', 'smtp'),`

### Installation Steps

1. **Install PHP dependencies:**

```bash
composer install --ignore-platform-req=ext-iconv
```

2. **Install Node.js dependencies (optional):**

```bash
npm install
```

3. **Generate application key:**

```bash
php artisan key:generate
```

4. **Create symbolic link for storage:**

```bash
php artisan storage:link
```

5. **Set up the database:**

    - Ensure a database named `simple_ecommerce` exists (or the name used in `.env`).

6. **Run database migrations:**

```bash
php artisan migrate
```

7. **Run database seeder:**

```bash
php artisan db:seed
```

**Note ! ! !**

After running the database seeder, the following users will be available:

-   **Admin User**  
    Email: `admin@user.com`  
    Role: `admin`

8. **To run the project, use the following commands:**

```bash
# Run the development server
npm run dev

# Start the PHP server
php artisan serve
```

9. **View project:**

    - Go to: localhost:8000

10. **To start the queue!**

```bash
# Run worker
php artisan queue:work

11. **To start the scheduler!**

# Start scheduler
php artisan schedule:work

*Note: There is a possibility that the scheduler or the queue job may crash. This could happen because Mailtrap has a rate limit on how many emails can be sent per second.*

**Enjoy! 😃**

