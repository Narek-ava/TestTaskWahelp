# CSV File Uploader API

This PHP project provides a simple API endpoint for uploading CSV files and processing them.

## Usage

1. Ensure you have PHP installed on your system.

2. Navigate to the project directory in your terminal.

3. Start the PHP built-in server by running:

```bash
php -S localhost:8000
```

1. Use Postman or any HTTP client to interact with the API.

```bash
POST http://localhost:8000/api/upload
```

1. Send a POST request to upload a CSV file:
   In the form-data tab of your POST request, add a key named fileToUpload and select the CSV file you wish to upload.
   Upon successful upload, the script will process the file and add the users to the database. You will receive a
   response indicating the operation's success or failure.

### API Endpoint

POST /api/upload: Uploads a CSV file containing user data. The file should be sent as form-data with the key
fileToUpload.

### Dependencies

No external dependencies are necessary for this project. It employs native PHP functionalities for file handling and
database interaction.

### Database Setup

Before running the project, set up a MySQL database. Use the following SQL queries to create the necessary tables:

```Bash
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    number VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE mailing_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    mailing_name VARCHAR(255) NOT NULL,
    mailing_text TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

```

Modify the database credentials in index.php to match your MySQL configuration.

### Troubleshooting

1. If encountering issues, verify that the PHP server is running correctly and that your database settings are accurate.

2. Check permissions for the uploads directory to ensure the server can write uploaded CSV files.

3. Ensure the CSV file to upload is properly formatted and devoid of errors.

```Bash

Feel free to adjust any details or add further information as needed. Let me know if you need further assistance!

```