# Smart Library Management System
The Smart Library Management System is a modern application designed to streamline and automate core library operations for both administrators and members. It goes beyond traditional management by incorporating dynamic features like a points-based gamification system, online PDF access, and intelligent fine calculation.

# Key Features
This system offers a comprehensive set of functionalities to ensure efficient library management and an enhanced member experience.

Member & Book Management (CRUD Operations):

Full Create, Read, Update, Delete (CRUD) capabilities for managing member and book records.

Book PDF Online Access:

Allows members to access and read book PDFs directly online within the system.

Authentication & Security:

Email-based OTP Verification for secure registration and login.

Forgot Password functionality utilizing OTP for secure account recovery.

Book Issuance and Return:

Efficient process for logging book issue and return transactions.

Dynamic Fine Calculation:

Fines are calculated dynamically based on the number of overdue days.

Gamified Points System:

Points are accrued by members based on their activity, such as book reading engagement.

Points are deducted when a member downloads an online book PDF.

Account Deactivation Policy:

Accounts of members who have been inactive (not logged in) for a prolonged period will be automatically deactivated.

Members must contact the Librarian/Admin for account re-activation.

User and Admin Reporting:

Report Generation for critical metrics (e.g., popular books, overdue records, active members).

Admin Profile Management:

Dedicated functionality for Administrators to manage and update their profiles.

Static Chatbot Integration:

A Static Chatbot is included to provide quick answers and guidance for common user queries, enhancing ease of use.

# Technology Stack (LAMP Stack Variant)

The system is built using the foundational web development technologies, following a traditional LAMP (Linux, Apache, MySQL, PHP) architectural pattern.

Backend/Server-side: PHP-Handles all server-side logic, database interactions, authentication, and dynamic fine/points calculations.

Database: MySQL-Used for structured storage of member data, book records, transaction logs, and points.

Frontend/Presentation: HTML5 & CSS3-Forms the structure and handles the styling for the user interface.

Interactivity: JavaScript-Provides client-side validation, dynamic content updates, and powers the static chatbot functionalit

# Getting Started
Follow these steps to set up and run the project locally.

Prerequisites
A running web server environment (like XAMPP, WAMP, or MAMP) which includes Apache, PHP, and MySQL.
A web browser.

Installation:
1. Move Files:
   
Place the project folder (smart-library-system) into your web server's root directory (e.g., htdocs for XAMPP).

2. Database Setup:

Access phpMyAdmin (or your preferred MySQL client).

Create a new database (e.g., smart_library_db).

Import the provided database schema and data file ([path-to-your-sql-file].sql).

3. Configure Database Connection:

Locate the database configuration file (e.g.,  database.php) and update the hostname, username, password, and database name to match your local setup.

4. Run the application:

Open your web browser and navigate to the project URL:

http://localhost/smart-library-system/

#Future Enhancements
[Suggest features you plan to add, like advanced search filters, more detailed analytics, etc.

