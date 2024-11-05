# Hawker Stalk 
Welcome to the official repository of <b>Hawker Stalk</b> !


# Project Setup Instructions

### Code Requirements

Our website primarily uses the following technologies:

1. **HTML** - for the content and structure of the webpage.
2. **CSS** - for styling and layout design.
3. **JavaScript** - for the logic and interactivity on the website.
4. **PHP** - for connecting and communicating with the database (requires additional setup).

You can use [Visual Studio Code](https://code.visualstudio.com/) to write all of these code files.

---

### Run Environment Setup

To run the website locally, you'll need to install a few tools and extensions.

#### 1. Install XAMPP

XAMPP is a local server that allows your computer to run websites and PHP code. While HTML, CSS, and JavaScript can run directly in a browser, XAMPP is necessary to execute PHP code and connect to a database.

- **Download and install** [XAMPP](https://www.apachefriends.org/index.html).
- **Project Placement**: Place the entire project folder (e.g., `Login`) in `C:\xampp\htdocs\`.
  
> **Optional:** Install a PHP Extension in VSCode for syntax highlighting and code suggestions:
> - In VSCode, go to **Extensions** > Search “PHP” > Install either **PHP Intelephense** or **PHP IntelliSense**.

#### 2. Install Microsoft Drivers for PHP for SQL Server

Since we use Azure SQL Server as our database, we need to add extensions that allow PHP to connect to it.

1. **Download and unzip** the [Microsoft Drivers for PHP for SQL Server](https://docs.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server).
   
2. **Verify PHP Information**:
   - Create a new PHP file in `C:\xampp\htdocs\` (e.g., `info.php`) with the following content:
     ```php
     <?php phpinfo(); ?>
     ```
   - Access this file in your web browser by typing `http://localhost/info.php`.
   - Note the version information about the PHP installation for the correct driver extensions.

3. **Install the Correct Extensions**:
   - Open the unzipped folder containing the extensions.
   - Select the extensions that match your PHP version, thread safety setting (nts if thread safety disabled), and architecture:
     - For example: `php_pdo_sqlsrv_82_ts_x64.dll` and `php_sqlsrv_82_ts_x64.dll`.
   - Copy both extensions to `C:\xampp\php\ext\`.

4. **Enable Extensions in PHP**:
   - Open `php.ini` located in `C:\xampp\php\`.
   - Under `;Dynamic Extensions`, add the following lines:
     ```ini
     extension=sqlsrv_82_ts_x64
     extension=php_pdo_sqlsrv_82_ts_x64
     ```

5. **Verify Installation**:
   - Refresh `http://localhost/info.php` in your browser.
   - Check for “sqlsrv” and “pdo_sqlsrv” to confirm that the extensions loaded successfully.

---

With these installations complete, your local environment should be set up to run the website and connect to the Azure SQL Server.


# Tech Stack
### Frontend: 
- HTML
- CSS
### Backend： 
- JavaScript
- PHP
- Microsoft Azure SQL Database

# External APIs
1. <b>Google Map API</b>
 - https://maps.googleapis.com/maps/api/js?key=AIzaSyCzh4khfKnyc3v9JIN4LhAR0ZxCw8Xsa_s
2. <b>Email API</b>
 - https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js

# Contributors
