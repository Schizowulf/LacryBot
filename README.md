## Environment:

1. Node.js
2. npm
3. Composer (php)
4. MySQL (phpadmin, MySQL Workbench etc)
5. Redis (for details see config/cache.php)
6. nginx, supervsiord (optionally)

## Installation
```
composer install
npm -i
//command below make sense only after right MySQL configuration according to .env file and official laravel documentation
php artisan migrate
```

## Usage
```
// Compile only js
npm run build
// Compile only css
npm run scss
// Compile js and css
npm run fbuild
// Watch for changes in js sources and compile them, if changes have been made
npm run watch
```

## Project structure
<p>
All css and js sources placed in /resources/. 
Compiled fiels in /public/. 
PHP + HTML in /resources/views/. 
Routes in /routes/web.php
</p>
