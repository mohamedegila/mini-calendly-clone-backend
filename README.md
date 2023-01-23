## Mini Calendly Clone

### System Requirements

-   php 7.3 or above
-   apache
-   mysql

### Installation steps

**Run the following commands**

-   composer install / update
-   copy .env.example as .env file and set database credentials, zoom credentials and email config
-   php artisan migrate
-   php artisan key:generate
-   php artisan serve
-   php artisan schedule:run [when needed for run cron job]

APIs documentation link:
https://documenter.getpostman.com/view/14478551/2s8ZDbVKvn
