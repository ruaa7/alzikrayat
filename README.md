# Project Name

Alzikrayat.

# Description

Alzikrayat is a simple photo-sharing web app where users can upload, browse, and comment on their favorite memories.
Built entirely from scratch in PHP with a custom MVC framework, no backend framework or ORM used.

# Technologies

- PHP
- HTML
- CSS
- JavaScript 
- MySQL
- Bootstrap 5
- Apache (via XAMPP) — local development web server

# How to Run
- VS Code:
 
1. Start MySQL.
2. Open the project folder in VS Code.
3. Open a new terminal inside VS Code.
4. Run PHP's built-in server** from the terminal:
    C:\xampp\php\php.exe -S localhost:8000 -t public
5. Open the site** in a browser at:
    http://localhost:8000

- XAMPP:

1. Start Apache and MySQL in XAMPP Control Panel.
2. Import the database schema:
   - Open phpMyAdmin (`http://localhost/phpmyadmin`).
   - Choose the file `database/schema.sql` and click Go.
   - This creates the `alzikrayat` database.
3. Place the project folder inside XAMPP's "htdocs" directory, so that
   "index.php" ends up at:
   C:\xampp\htdocs\alzikrayat\public\index.php
4. Open the site in a browser at:
   localhost:8000/alzikrayat 
   or
   http://localhost/alzikrayat/public/
   

# Student Name

Ruaa Yousif Mohammed Al-amin
