# PPP2 Task Management System

A web-based task management application built with PHP, MySQL, and Bootstrap. This application allows users to manage tasks, groups, and collaborate with role-based access control.

## Features

- User registration and authentication
- Password reset functionality
- Role-based access control (Admin, Manager, Executor)
- Task creation, editing, and management
- Group management for organizing tasks
- File attachments for tasks
- Comments on tasks
- Dashboard with task filtering and overview
- Email notifications using PHPMailer

## Technologies Used

- **Backend**: PHP 8.0+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML, CSS, Bootstrap 4, jQuery, Ajax
- **Email**: PHPMailer
- **Server**: Apache (via XAMPP)

## Installation

### Prerequisites

- XAMPP (or similar with PHP 8.0+, MySQL, Apache)
- Composer (for dependency management)

### Steps

1. **Clone or Download the Project**
   - Copy the project files to your XAMPP htdocs directory (e.g., `C:\xampp\htdocs\ppp2`)

2. **Install Dependencies**
   - Open a terminal in the project root
   - Run `composer install` to install PHPMailer

3. **Database Setup**
   - Start XAMPP and ensure MySQL is running
   - Create a new database named `ppp2`
   - Import the database schema from `DATABASE/ppp2.sql`

4. **Configuration**
   - Copy `includes/config.example.php` to `includes/config.php`
   - Update the configuration values in `config.php` with your database credentials and email settings
   - Default database: `ppp2`, user: `root`, password: (empty)
   - Email settings need to be configured (e.g., for Mailtrap or your SMTP provider)

5. **File Permissions**
   - Ensure the `uploads/` directory is writable by the web server

## Usage

1. Start XAMPP (Apache and MySQL)
2. Access the application at `http://localhost/ppp2/`
3. Register a new account or login with existing credentials
4. Use the dashboard to create and manage tasks and groups

### User Roles

- **Admin**: Full access to all features, user management
- **Manager**: Can create and manage tasks, groups
- **Executor**: Can view and update assigned tasks

## Project Structure

- `classes/`: PHP classes (Grupa, TaskFilter, Zadatak)
- `css/`: Stylesheets (Bootstrap, custom styles)
- `includes/`: Configuration and utility functions
- `js/`: JavaScript files (jQuery, Bootstrap)
- `partials/`: Reusable PHP components
- `uploads/`: File attachments directory
- `vendor/`: Composer dependencies
- `DATABASE/`: Database schema

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support or questions, please contact the development team.