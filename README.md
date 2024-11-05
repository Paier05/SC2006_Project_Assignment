# Hawker Stalk 
<p>Welcome to the official repository of <b>Hawker Stalk</b> !<br>
Hawker Stalk is a one-stop platform that connects tourists, residents and hawkers to preserve Singapore’s unique Hawker culture. It allows users to explore different hawker centers in Singapore and the food choices available in a more convinient way. <br><br>
By establishing Hawker Stalk, we hope to enhance Singpore's Hawker culture in a more modern and interactive way, at the same time promoting the culture wider to tourists, providing them with a unique Singapore experience by making exploring different hawker centers in town fun and adventurous. </p>
---

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

# Pre-configured Users
|Domain |Email |Password|
|-----|-----|-----|
|Admin|NA|NA|
|Customer|ccc@ddd.com|NA|
|Customer|NA|NA|
|Hawker|hawker123@test.com|Hawker123#|
|Hawker|hawker666@test.com|Hawker666#|
---
# Documentation
## API Docs
# App Design
## Overview
## File Structure

# Design Pattern

## MVC Pattern

The HawkerStall application follows the MVC (Model-View-Controller) design pattern to separate business logic, user interface, and control functions.

### Model
The Model layer contains the core logic and data management for the application, specifically for handling HawkerStall-related functionality, including:
- **Account verification**: Authenticating user accounts and stall owners.
- **Review and fault report storage**: Managing and storing user reviews, ratings, and fault reports.
- **Stall data management**: Handling data related to stall details, including menu items, operating hours, and location.

### View
The View layer is responsible for presenting data in a user-friendly manner, ensuring users have a clear interface to interact with:
- **Displaying interfaces**: Showing various pages and elements of the user interface.
- **Dynamic data display**: Presenting real-time information like reviews, ratings, and operating hours, providing users with up-to-date stall information.

### Controller
The Controller layer acts as the intermediary between the Model and the View, managing user inputs and ensuring data integrity:
- **Form processing**: Handling data submissions from the user.
- **Data validation**: Ensuring the data provided by the user is valid and follows the correct format.
- **User action responses**: Interpreting user actions and sending updates to the Model or View as needed.

---

## Observer & Strategy Patterns in MVC

To improve flexibility and maintainability, we use the Observer and Strategy design patterns within the MVC framework.

### Observer Pattern
The Observer Pattern is implemented between the Model and View layers. It allows automatic updates in the View whenever the Model undergoes changes. This pattern is used for real-time data updates, such as:
- **Real-time operating status updates**: Instantly reflecting any changes in stall availability.
- **Real-time reviews and ratings**: Automatically updating displayed reviews and average ratings when new ones are added.
- **Menu and operating hour updates**: Immediately showing changes when stall owners update their offerings or hours.

### Strategy Pattern
The Strategy Pattern is applied in the Controller layer to manage varying behaviors without hardcoding solutions. It enables flexible implementation of different business logic and functionality, such as:
- **Search and filter options**: Allowing multiple filtering strategies based on user preferences.
- **Sorting and ranking**: Implementing customizable ranking and sorting for search results.
- **Rating calculation**: Adapting various calculation methods for different rating categories.
  
---

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
