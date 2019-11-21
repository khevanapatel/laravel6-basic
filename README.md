# laravel6-basic
creating one laravel 6 demo for startup project


Step1: First clone the project

Step2: Install vendor

	command: "composer install"

Step3: Create your new database for this laravel project and update ypur .env credential for hostname, database name, username

DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

Step4: Run migrate command to set datanbase tables

	command: "php artisan migrate" 

Step5: Now ready to run project

	command: "php artisan serve"