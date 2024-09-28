# Authorly - Book and Author Management Application

This project is a full-stack application built with **Laravel** on the backend and **React** on the frontend, with API endpoints and authentication mechanisms to manage books and authors. It supports both API-based and Inertia-based responses, allowing seamless interaction from both frontend and backend.

## Features

-   **Authentication**: Implemented using HTTPOnly cookies for security with Laravel Sanctum.
-   **API CRUD Operations**: Full API support for managing books and authors.
-   **Frontend with React**: Built with React and React Router for frontend views and navigation.
-   **Inertia Responses**: Supports both Inertia and API-based frontend interactions.
-   **Authorization and Guards**: Ensures proper access control for authenticated users.
-   **Folder Structure**: Organized to separate concerns for API and web routes/controllers.

## Technologies Used

-   **Backend**: Laravel, Sanctum (for API token authentication), MySQL, Laravel Scout (for search).
-   **Frontend**: React, Vite, Inertia.js, React Router.
-   **Database**: MySQL.

## Setup Instructions

### Prerequisites

-   PHP 8.1 or above
-   Composer
-   Node.js and npm
-   MySQL
-   Laravel Sanctum for API authentication
-   Laravel Scout for search functionality

### Backend Setup

1. Clone the repository and navigate to the project directory:

    ```bash
    git clone https://github.com/your-repo/authorly.git
    cd authorly
    ```

2. Install PHP dependencies using Composer:

    ```bash
    composer install
    ```

3. Create a `.env` file and set up your database configuration:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Configure MySQL database settings in `.env`:

    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=authorly
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. Run migrations to create database tables:

    ```bash
    php artisan migrate
    ```

6. Set up Laravel Sanctum for authentication:

    ```bash
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    php artisan migrate
    ```

7. Install Laravel Scout for search functionality:

    ```bash
    composer require algolia/scout-extended
    ```

8. Configure your Algolia credentials in `.env`:

    ```plaintext
    ALGOLIA_APP_ID=your-algolia-app-id
    ALGOLIA_SECRET=your-algolia-api-key
    ```

9. Seed the database with initial data:

    ```bash
    php artisan db:seed
    ```

10. Serve the application:
    ```bash
    php artisan serve
    ```

### Frontend Setup

1. Navigate to the `resources/js` directory and install JavaScript dependencies:

    ```bash
    npm install
    ```

2. Build the assets using Vite:

    ```bash
    npm run dev
    ```

3. Configure the frontend routes in `resources/js/app.jsx`:

    ```javascript
    import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
    import Home from "./Pages/Home";
    import AuthorList from "./Pages/AuthorList";
    // Import other components

    const App = () => (
        <Router>
            <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/authors" element={<AuthorList />} />
                {/* Define other routes */}
            </Routes>
        </Router>
    );

    export default App;
    ```

4. Set up the catch-all route in `routes/web.php` to serve the React app:
    ```php
    Route::get('/{any}', function () {
        return view('app'); // Returns the main app view
    })->where('any', '.*');
    ```

### API Endpoints

1. **Authentication** (HTTPOnly Cookies via Sanctum):

    - `POST /api/login`: Logs in a user and sets the session cookie.
    - `POST /api/register`: Registers a new user.

2. **Books**:

    - `GET /api/books`: Retrieve a list of books.
    - `POST /api/books`: Create a new book.
    - `GET /api/books/{id}`: Get a specific book by ID.
    - `PUT /api/books/{id}`: Update an existing book.
    - `DELETE /api/books/{id}`: Delete a book.

3. **Authors**:
    - `GET /api/authors`: Retrieve a list of authors.
    - `POST /api/authors`: Create a new author.
    - `GET /api/authors/{id}`: Get a specific author by ID.
    - `PUT /api/authors/{id}`: Update an existing author.
    - `DELETE /api/authors/{id}`: Delete an author.

### Frontend Pages

1. **Home Page** (`/`):

    - Displays a welcome message or dashboard.

2. **Author List** (`/authors`):

    - Shows a list of authors with options to edit or delete.

3. **Book List** (`/books`):

    - Displays a list of books with CRUD operations.

4. **Create/Edit Author**:

    - Forms for adding and updating authors.

5. **Create/Edit Book**:
    - Forms for adding and updating books.

### Authentication

-   HTTPOnly cookies are used for authentication, with Laravel Sanctum managing the session and API token authentication.
-   Use the `Login`, `Register`, and `Dashboard` React components to handle user authentication and session management.

### Search Functionality

-   Integrated with Laravel Scout for searching books by title or author. The search index is handled by Algolia.

### Folder Structure

```
authorly/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/         // API controllers
│   │   │   └── Web/         // Inertia/web controllers
├── database/
│   ├── migrations/          // Database migration files
│   └── seeders/             // Database seeders
├── resources/
│   ├── js/
│   │   ├── Pages/           // React components
│   │   ├── Components/      // Shared components
├── routes/
│   ├── api.php              // API routes
│   └── web.php              // Web routes (Inertia)
```

### Testing

-   Unit tests for books and authors can be found in `tests/Feature/BookTest.php` and `tests/Feature/AuthorTest.php`.

Run the tests using:

```bash
php artisan test
```
