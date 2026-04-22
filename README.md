# PASS - ASCD System

PASS - ASCD System is a web-based academic scheduling system built with Laravel. It is designed for admin-only use and helps manage faculty, rooms, courses, sets, subjects, and class schedules with conflict detection and timetable generation.

## Main Features

- Admin authentication
- CRUD for users, faculties, rooms, courses, sets, subjects, and schedules
- Schedule conflict detection for:
  - faculty overlap
  - room overlap
  - set overlap
  - room capacity vs student count
- Face-to-face and online class support
- Timetables for:
  - faculty
  - room
  - course and set
  - online classes by course and set
- Reports for:
  - faculty load summary
  - faculty availability
  - conflict report
  - room utilization
  - batch export
- PDF export for timetables
- Responsive admin interface

## Tech Stack

- Laravel
- PHP
- MySQL
- Bootstrap
- Vite
- DomPDF
- Choices.js

## System Rules

- Class days are from Monday to Saturday only.
- Time slots run from 8:00 AM to 8:00 PM in 15-minute intervals.
- Rooms are required only for face-to-face classes.
- Online classes do not require a room.
- Faculty, set, and room conflicts are blocked during schedule creation and update.

## Installation

1. Clone the repository.
2. Install PHP dependencies:

```bash
composer install
```

3. Install Node dependencies:

```bash
npm install
```

4. Copy the environment file:

```bash
copy .env.example .env
```

If you are using Git Bash, use:

```bash
cp .env.example .env
```

5. Update the `.env` file with your database credentials.
6. Generate the application key:

```bash
php artisan key:generate
```

7. Run migrations and seeders:

```bash
php artisan migrate --seed
```

8. Build frontend assets:

```bash
npm run build
```

9. Start the local server:

```bash
php artisan serve
```

10. Open the app in your browser:

```text
http://127.0.0.1:8000
```

## Default Usage

- Only the admin user can access the system.
- Create faculties, rooms, courses, sets, and subjects first.
- Create schedules after master data is ready.
- Use the Timetables page to open generated timetable views.
- Use the Reports page for faculty load, availability, conflicts, room utilization, and batch export.

## Notes

- If PDF export is used, make sure DomPDF is installed correctly.
- If ZIP batch export is used on XAMPP, enable the PHP `zip` extension in `php.ini`.
- If you make frontend changes, run:

```bash
npm run build
```

## Project Structure

- `app/Http/Controllers` - request handling and business logic
- `app/Models` - Eloquent models
- `resources/views` - Blade templates
- `resources/js` - frontend behavior
- `resources/sass` - custom styling
- `routes/web.php` - web routes

## Author

Developed for the PASS academic scheduling workflow.

## License

This project is licensed under the MIT License. See the `LICENSE` file for details.
