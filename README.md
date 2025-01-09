- Modeling Agency Management API

To Run the Project:

Step 1: Install a Local Server

Use WampServer or XAMPP Server to run the project locally.

Step 2: Create the Database

Open your database management tool (e.g., phpMyAdmin).

Create a new database named modeling_agency.

Step 3: Install Dependencies

Run the following command to install the necessary dependencies:

composer install

Step 4: Run Migrations and Seeders

You can run migrations and seeders together or separately:

Option 1: Run Migrations and Seeders Together

php artisan migrate:fresh --seed

Option 2: Run Migrations and Seeders Separately

Run the migrations:

php artisan migrate

Seed the database:

php artisan db:seed

(Note: The database has been pre-populated with dump data for testing purposes.)

Step 5: Start Testing the API

Use Postman or your preferred API testing tool to test the endpoints.

Everything should now be set up and ready to use!

