# SocialNet

A simple social network web application built with PHP, MySQL, Nginx, and Linux.

## Tech Stack
- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP 8.3
- **Database:** MySQL
- **Web Server:** Nginx

## Features
- User account creation (Admin)
- Sign in / Sign out
- Home page with friends list and people you may know
- Add / remove friends
- View user profiles
- Edit profile bio
- About page

## Pages
| Page | URL |
|---|---|
| Admin | `/admin/newuser.php` |
| Sign In | `/socialnet/signin.php` |
| Home | `/socialnet/index.php` |
| Profile | `/socialnet/profile.php` |
| Settings | `/socialnet/setting.php` |
| About | `/socialnet/about.php` |
| Sign Out | `/socialnet/signout.php` |

## Setup

### 1. Import the database
```bash
sudo mysql < db.sql
```

### 2. Configure DB connection
Edit `socialnet/config.php` and set your MySQL username and password.

### 3. Copy files to web root
```bash
sudo cp -r socialnet/ admin/ /var/www/html/
```

### 4. How to access
http://localhost/admin/newuser.php Create users
http://localhost/socialnet Access web
