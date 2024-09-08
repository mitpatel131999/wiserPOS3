# PHP Web Application

This is a PHP web application for managing items and their reviews, with a MySQL database backend. The application is designed to run locally using PHP's built-in server.

## Prerequisites

- PHP installed on your machine.
- MySQL server installed and running.

## Setup Instructions

1. **Clone the Repository**:

   Clone the repository or download the project files to your local machine.

2. **Set Up the Database**:

   - Open a terminal and log into the MySQL CLI:

     ```sh
     mysql -u root -p
     ```

   - Create the database and tables by running the following SQL commands:

     ```sql
     CREATE DATABASE yourdbname;

     USE yourdbname;

     CREATE TABLE items (
         id INT AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(255) NOT NULL,
         manufacturer VARCHAR(255) NOT NULL
     );

     CREATE TABLE reviews (
         id INT AUTO_INCREMENT PRIMARY KEY,
         item_id INT NOT NULL,
         reviewer_name VARCHAR(255) NOT NULL,
         rating INT CHECK (rating BETWEEN 1 AND 5),
         review_text TEXT NOT NULL,
         review_date DATETIME DEFAULT CURRENT_TIMESTAMP,
         FOREIGN KEY (item_id) REFERENCES items(id)
     );
     ```

3. **Configure PHP Files to Connect to MySQL**:

   - Ensure your PHP files have the correct database connection details:

   ```php
   $conn = new PDO('mysql:host=localhost;dbname=yourdbname', 'root', 'yourpassword');
