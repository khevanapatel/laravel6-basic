# laravel6-basic
creating one laravel 6 demo for startup project


Step1: First clone the project

Step2: Install vendor

	command: "composer install"

Step3: Create your new database for this laravel project and Create a .env file without an APP_KEY= line.
 set hostname, database name, username

DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

Step4: Run php artisan key:gene

Step5: Run migrate command to set datanbase tables

	command: "php artisan migrate" 

Step6: Now ready to run project

	command: "php artisan serve"

Step7: Login to Dashboard

	Admin: 
		Email : admin@gmail.com
		password : 123456789

	User: 
		Email: user@gmail.com
		password: 123456789