# disease-management-system
Web and Python-based system for disease tracking, patient management, and medical data analysis.

## Key Features
* Patient and doctor management
* Add, modify, and delete medical records
* Secure authentication (login)
* PHPMailer to send email
* Export to PDF and Excel
* Responsive interface in PHP/HTML/CSS/JS
* Tkinter interface for certain actions triggered by the web application
* Matplotlib graphs are generated when specific buttons are clicked on the web interface

## Technologies Used
* Frontend: HTML, CSS, JavaScript
* Backend: PHP, Python
* Database: MySQL
* Statistics: Python (Pandas, Matplotlib)
* Dependency Management: Composer

## Installation
* Clone the repository
git clone https://github.com/elghalbouni-oumaima/disease-management-system.git
* cd MANAGEMENTDESEASE
* Install PHP Dependencies
Ensure that you have Composer installed (PHP dependency management tool). Then, install the required PHP dependencies: composer install
* Set Up the Database
Create a MySQL database: diseasemanagement
Import the database schema from Login/database.sql:
mysql -u root -p diseasemanagement < Login/database.sql
* Update Database Credentials
Open the Login/database.php file and update your database connection details.
* Install  Dependencies
Make sure you have Python installed. Then, install the required Python dependencies for generating statistical graphs and handling Tkinter:
pip install -r Login/requirements.txt

## Usage
Web Application (PHP)
Launch the web application on your server (e.g., XAMPP) and access it via http://localhost/managementdesease.
When a user clicks on a button (e.g., to add a patient, diagnosis, etc.), the Tkinter interface will appear to handle those specific actions.
When specific buttons related to disease statistics are clicked, Matplotlib will automatically generate the corresponding graphs.

## Contribute
Contributions are welcome! To contribute:

* Fork the repository.
* Create a new branch for your feature (git checkout -b feature-name).
* Commit your changes (git commit -am 'Add new feature').
* Push your changes to your fork (git push origin feature-name).
* Open a pull request to merge your changes into the main repository.







