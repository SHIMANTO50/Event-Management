# Event Management System

This is a simple event management system built using raw PHP and MySQL.

# Live Website Link

Live Link: https://tanjimul.xyz/

# Default Credentials

### Admin Account

- **Email:** `admin@admin.com`
- **Password:** `123`

### User Account

- **Email:** `user@gmail.com`
- **Password:** `12345`

## Features

- User authentication (login/logout)
- User can Create, edit, and delete events
- Event listing with search functionality
- Pagination for event listings
- Search and filter events
- Register for events with limited capacity
- After Login Admin redirect to the admin dashboard
- Admin can download attendee lists for specific events in CSV format

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

## Configure the Database Connection

Open `includes/db.php` and update the following settings:

```php
$host = "localhost";
$user = "root"; // Default Laragon/XAMPP MySQL user
$password = ""; // Default Laragon/XAMPP MySQL password (empty)
$database = "event_management";
```

## Configure the Config File

Open `includes/config.php` and update the following settings:

You have to define the base url here. In my case I have used Laragon and my I set my base url like below:

```php
<?php
 define('BASE_URL', 'http://project.test/');
?>
```
