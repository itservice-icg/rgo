@echo off
cd /d "%~dp0"

echo Running database migrations...
php artisan migrate

echo.
echo Clearing Laravel caches...
php artisan optimize:clear

echo.
echo Linking public storage...
php artisan storage:link

echo.
echo Starting Laravel development server...
php artisan serve
