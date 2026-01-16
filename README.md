# 📚 Digital Library

A modern web-based digital library management system built with Laravel and Vue.js. This application allows users to browse, rent, and manage books, rate their reading experiences, and connect with other readers through a social friendship system.

## ✨ Features

### 📖 Book Management
- Browse and search available books
- View detailed book information (title, author, availability)
- Track total copies and available copies in real-time
- Active/inactive book status management

### 🔄 Rental System
- Rent books with automatic due date tracking
- Return books and update availability
- View rental history and active rentals
- Due date management to prevent overdue returns

### ⭐ Rating & Reviews
- Rate books after reading
- View ratings from other users
- Help the community discover great books

### 👥 Social Features
- Friend system with request/accept functionality
- Connect with other readers
- Share reading experiences with friends
- View friends' profiles and reading activities

### 🔐 Authentication
- User registration and login
- Secure authentication system
- User profile management

## 🛠️ Tech Stack

### Backend
- **PHP 8.2+** - Modern PHP features and performance
- **Laravel 12** - Elegant PHP framework for web artisans
- **MySQL/SQLite** - Relational database management
- **Inertia.js** - Modern monolith architecture

### Frontend
- **Vue.js 3** - Progressive JavaScript framework
- **Tailwind CSS 4** - Utility-first CSS framework
- **Vite** - Next-generation frontend tooling
- **Axios** - Promise-based HTTP client

### Development Tools
- **Laravel Pint** - Code style fixer for PHP
- **PHPUnit** - PHP testing framework
- **Laravel Sail** - Docker development environment
- **Concurrently** - Run multiple commands simultaneously

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL or SQLite
- Git

## 🚀 Installation

### Quick Setup

```bash
# Clone the repository
git clone https://github.com/LucasBrisch/digital-library.git
cd digital-library

# Run the automated setup script
composer setup
```

### Manual Setup

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# Then run migrations
php artisan migrate

# Build frontend assets
npm run build
```

## 🎮 Usage

### Development Mode

Run all services concurrently (server, queue, logs, and Vite):

```bash
composer dev
```

This will start:
- Laravel development server (http://localhost:8000)
- Queue worker for background jobs
- Real-time log monitoring with Pail
- Vite dev server with HMR

### Individual Commands

```bash
# Start the development server
php artisan serve

# Watch and compile frontend assets
npm run dev

# Build for production
npm run build

# Run tests
composer test
```

## 📁 Project Structure

```
digital-library/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Auth/          # Authentication controllers
│   │       ├── BookController.php
│   │       ├── RentalController.php
│   │       ├── RatingController.php
│   │       ├── FriendshipController.php
│   │       └── UserController.php
│   └── Models/
│       ├── Book.php           # Book model with rentals
│       ├── Rental.php         # Rental management
│       ├── Rating.php         # Book ratings
│       ├── Friendship.php     # Friend system
│       └── User.php           # User model
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/              # Database seeders
├── resources/
│   ├── js/
│   │   ├── Pages/
│   │   │   ├── Auth/         # Login & Register
│   │   │   ├── Books/        # Book views
│   │   │   └── User/         # User & Friends pages
│   │   └── app.js            # Vue.js app entry
│   └── views/                # Blade templates
├── routes/
│   ├── web.php               # Web routes
│   └── api.php               # API routes
└── tests/                    # Application tests
```

## 🧪 Testing

```bash
# Run all tests
composer test

# Run specific test file
php artisan test tests/Feature/BookTest.php

# Run tests with coverage
php artisan test --coverage
```

## 🔒 Security

If you discover any security vulnerabilities, please create an issue or contact the repository owner directly.

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Author

**Lucas Brisch**
- GitHub: [@LucasBrisch](https://github.com/LucasBrisch)

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/LucasBrisch/digital-library/issues).

---

Built with ❤️ using Laravel and Vue.js
