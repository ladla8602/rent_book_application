## Intro
Renting Book Application - Backend uses Laravel (V7) and Frontend uses Angular 10.
This application has the following features.
I. There will be two roles admin and renter.
II. Admin will have following roles
    1. Add books ( books will have: name, author, publish date, price)
    2. Update books details 
    3. Remove books Details
III. Renter will have following roles
    1. Rent the books
    2. Return the books

IV. Renter can download PDF of invoice.

## Technical Details
I. This project is divided into to folders as:

1. backend - Build on Laravel for api\'s which are going to consume by angular frontend.
2. frontend - Build on Angular for Application interface and UI for this app by consuming laravel api\'s.

3. I am using queue for the process of invoice generation during renting book process.

II. Installation
Note: Use Apache server to host backend & MySql database 
Step 1: Copy the backend folder files inside www/var/html/
Step 2: Edit the .env file replace the following
        DB_DATABASE=<Your Database Name>
        DB_USERNAME=<Database Username>
        DB_PASSWORD=<Database Password>
Step 3: Bring terminal to the root folder of backend in my case it is '/www/var/html/'
        Type: composer install <if couposer command not found google search it on your own>
        Type: ```php php artisan migrate```
        Type: ```php php artisan db:seed```
        Type: ```php php artisan passport:install```

Step 4: Run this command to generate jobs table to store jobs in queue
        ```php php artisan queue:table```

Step 5: You just need to update your “.env” file
        QUEUE_DRIVER=database
        QUEUE_CONNECTION=database

Step 6: You need to install 'gd' a php extention (google it how to install)
        Run this command to check if this extention is enabled in your system
        ```php php -m```
        
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# rent_book_application
