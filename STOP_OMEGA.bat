@echo off
title OMEGA SYSTEM - STOPPING...
color 0C
echo.
echo  [!] Arret de tous les serveurs Omega en cours...

:: Ferme proprement les processus PHP, Python et Node
taskkill /F /IM php.exe /T 2>nul
taskkill /F /IM python.exe /T 2>nul
taskkill /F /IM node.exe /T 2>nul

echo.
echo  [OK] Tous les terminaux ont ete fermes.
echo.
pause
