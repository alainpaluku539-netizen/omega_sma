@echo off
title OMEGA SYSTEM - STARTING...
color 0B
echo.
echo  =========================================
echo       OMEGA SMART HOME - DASHBOARD
echo  =========================================
echo.

:: 1. Lancement de Vite (Interface CSS/JS)
echo [*] Starting VITE (Frontend)...
start "OMEGA: VITE" cmd /k "npm run dev"

:: 2. Lancement de Laravel Reverb (WebSockets Temps Reel)
echo [*] Starting REVERB (WebSockets)...
start "OMEGA: REVERB" cmd /k "php artisan reverb:start"

:: 3. Lancement de la Gateway Python (Bridge MQTT)
echo [*] Starting PYTHON GATEWAY (MQTT)...
start "OMEGA: PYTHON" cmd /k "python app.py"

echo.
echo  [OK] Tout est lance ! 
echo  [URL] http://smart-home.test
echo.
echo  =========================================
pause
