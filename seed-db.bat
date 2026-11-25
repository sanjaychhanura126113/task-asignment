@echo off
set "PATH=C:\Users\chhan\php;%PATH%"
echo Re-running migrations and database seeders...
php artisan migrate:fresh --seed
echo Database reset and seeded successfully!
pause
