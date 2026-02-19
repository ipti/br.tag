@echo off
REM SQL Migration Helper Script for Windows
REM Simplifies execution of SQL migrations across multiple TAG databases

setlocal enabledelayedexpansion

set CONTAINER_NAME=tag-app
set YIIC_PATH=/app/app/yiic
set MIGRATIONS_DIR=/app/app/migrations

if "%~1"=="" (
    echo Error: SQL file path is required
    echo.
    echo Usage:
    echo   migrate.bat ^<sql-file^> [--dry-run]
    echo.
    echo Examples:
    echo   migrate.bat app\migrations\inventory_complete.sql
    echo   migrate.bat app\migrations\inventory_complete.sql --dry-run
    exit /b 1
)

set SQL_FILE=%~1
set DRY_RUN=

if "%~2"=="--dry-run" (
    set DRY_RUN=--dry-run
    echo Running in DRY-RUN mode (no changes will be made)
    echo.
)

REM Convert Windows path to container path
set CONTAINER_SQL_FILE=%SQL_FILE:\=/%
if not "!CONTAINER_SQL_FILE:~0,1!"=="/" (
    if "!CONTAINER_SQL_FILE:~0,4!"=="app/" (
        set CONTAINER_SQL_FILE=/app/!CONTAINER_SQL_FILE!
    ) else (
        set CONTAINER_SQL_FILE=%MIGRATIONS_DIR%/!CONTAINER_SQL_FILE!
    )
)

echo Executing SQL migration...
echo Container: %CONTAINER_NAME%
echo SQL File: !CONTAINER_SQL_FILE!
echo.

docker exec %CONTAINER_NAME% php %YIIC_PATH% sqlmigration run !CONTAINER_SQL_FILE! %DRY_RUN%

if %ERRORLEVEL% EQU 0 (
    echo.
    echo Migration completed successfully
) else (
    echo.
    echo Migration failed
    exit /b 1
)
