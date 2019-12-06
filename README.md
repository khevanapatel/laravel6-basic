<!-- # laravel6-basic -->
# Laravel 6 demo project for startup


Step1: First clone the repository

Step2: Instalation

	composer install

Step3: Create your new database for this laravel project and Create a .env.
 set hostname, database name, username, password

	APP_KEY=
	DB_DATABASE=
	DB_USERNAME=
	DB_PASSWORD=

Step4: Run the following command to generate a key

	php artisan key:generate

Step5: Run migrate command to set database tables

	php artisan migrate

Step6: Now ready to run project

	php artisan serve

Step7: Login to Dashboard

Admin Credentials

		Email : admin@gmail.com
		password : admin123!


User Credentials

		Email: user@gmail.com
		password: user123!
