@echo off
set "PATH=C:\Users\chhan\php;%PATH%"
echo ============================================================
echo   TaskFlow - Starting Local Server + Public Live Tunnel
echo ============================================================
start /B php artisan serve --port=8000
timeout /t 2 /nobreak >nul
echo Local server running at http://127.0.0.1:8000
echo.
echo Launching Cloudflare Public HTTPS Tunnel...
echo ============================================================
C:\Users\chhan\cloudflared.exe tunnel --url http://127.0.0.1:8000
pause
