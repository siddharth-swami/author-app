# Author Management System

## Project Overview
This project is a Laravel-based Author Management System that allows users to add new authors via an AJAX-based form. The author data is validated and sent to a third-party API using an authentication token. The system ensures a smooth user experience with real-time error handling and form validation.

## Technologies Used
- **PHP**: 8.1+
- **Laravel**: 10.x
- **jQuery**: For AJAX requests
- **Bootstrap**: For UI styling (Optional)

Features
1. Author Management

    List all authors with pagination.
    Add a new author using an AJAX request and store it via a third-party API.
    View details of an author, including their biography, gender, and books.
    Delete authors with a confirmation prompt.

2. Book Management

    Display a list of books associated with an author.
    Add a new book with fields like title, release date, description, ISBN, format, and number of pages.
    Handle errors returned by the API, including missing fields and incorrect data formats.
    Implement AJAX-based deletion of books.

3. Pagination & Sorting

    Implement pagination by fetching a limited number of records per request.
    Display total entries correctly in the table footer.
    Enable sorting of records by column headers.

4. Search with Debounce

    Implement a search bar with a debounce function to reduce API calls.
    Wait for the user to stop typing before making an API request.

5. API Token Authentication

    Pass API tokens in Laravel HTTP client requests when calling the third-party API.
    Ensure secure communication between the backend and external APIs.

6. Global User Information Display

    Show logged-in user’s first name and last name in the footer of every page.

## Installation & Setup
### Step 1: Clone the Repository
```sh
git clone https://github.com/your-repository/author-management.git
cd author-management
```

### Step 2: Install Dependencies
```sh
composer install
```

### Step 3: Set Up Environment Variables
Copy the `.env.example` file and configure the database and third-party API URL:
```sh
cp .env.example .env
```
Edit `.env` file:
```sh
APP_NAME="Author Management"
APP_URL=http://localhost
THIRD_PARTY_API_URL="https://third-party-api.com"
```

### Step 4: Generate Application Key
```sh
php artisan key:generate
```

### Step 5: Run Migrations
```sh
php artisan migrate
```

### Step 6: Start the Development Server
```sh
php artisan serve
```
The project will be available at `http://127.0.0.1:8000`.


## Code Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   
resources/
├── views/
│     
routes/
├── web.php
```

## Developed By
👤 **Siddharth**
- **Role:** Full-Stack Developer
- **Expertise:** Laravel, PHP, JavaScript, React, DevOps
- **GitHub:** [github.com/your-profile](https://github.com/your-profile)

## License
This project is licensed under the MIT License.

