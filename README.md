# This project is a simple blog api with laravel

## steps for using this project

1. Clone the repository
2. Run `composer install` in the root directory
3. Create database and edit .env file
4. Run `php artisan migrate` to create tables and `php artisan db:seed --class=UserSeeder` for create user
5. Run the project with `php artisan serve` command
6. Get the [link](http://127.0.0.1:8000/request-docs/) url
7. Use the endpoints in postman

---

**_Notice_**

##### At the each login api_token regenerated

###### Use the api_token in authorization tab and Beare token

###### User Admin can create, edit, update, delete, posts
