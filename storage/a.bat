@echo off
start "" "C:\Program Files\Mozilla Firefox\firefox.exe" %1 http://127.0.0.1:8000
timeout /t 3 >nul
"C:\nircmd\nircmd.exe" win activate ititle "Mozilla Firefox"
"C:\nircmd\nircmd.exe" sendkeypress F11
