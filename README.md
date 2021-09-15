Go to the project folder 

Update composer 
```
composer update
```

## Step 2
Copy ```.env.example``` file to ```.env```

For Unix
```
cp .env.example .env
```
For Windows
```
copy .env.example .env
```

Next, run this follow commands

!! YOU NEED TO INSTALL NODE.JS FOR USE NPM !! 

For install all NPM package

```
npm install
```

```
php artisan key:generate
bower install
gulp
```

Configure your ```.env``` file and run :
```
php artisan migrate
```

Run Tenancy to create a tenant
```
php artisan tenancy:create showcase marcus@mail.com
```
System Login name will be marcus@mail.com, password will be mP@ssw0rd

Run command to start laravel server
php artisan serve

browse showcase.localhost:8000 to view project