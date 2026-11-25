@echo off
set "PATH=C:\Users\chhan\php;%PATH%"
echo ============================================================
echo   TaskFlow - Task Assignment Management Portal
echo ============================================================
echo   Admin Login:   admin@taskflow.local   (Password: password123)
echo   Member Login:  sarah@taskflow.local   (Password: password123)
echo   Local URL:     http://127.0.0.1:8000
echo ============================================================
echo Starting Laravel Server...
php artisan serve
pause
