# Event Management System

This is a simple event management system built using raw PHP and MySQL.

## Features

- User authentication (login/logout)
- Create, edit, and delete events
- Search and filter events
- Register for events

# Default Credentials

After setting up the database, you can use the following default credentials to log in:

### Admin Account

- **Email:** `admin@example.com`
- **Password:** `admin123`

### User Account

- **Email:** `user@example.com`
- **Password:** `user123`

## Installation

### Using XAMPP

1. Download and install [XAMPP](https://www.apachefriends.org/index.html).
2. Start **Apache** and **MySQL** from XAMPP Control Panel.
3. Place the project folder inside `htdocs` (e.g., `C:\xampp\htdocs\myproject`).
4. Import the database using `phpmyadmin`:
   - Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Create a new database (e.g., `events_db`).
   - Import the provided `.sql` file.

### Using Laragon

1. Download and install [Laragon](https://laragon.org/).
2. Start **Apache** and **MySQL** from Laragon.
3. Place the project folder inside `C:\laragon\www\myproject`.
4. Open a terminal in Laragon and run:
   ```sh
   cd C:\laragon\www\myproject
   ```
