# Verification

- Appointment regression tests: `APP_ENV=testing DB_CONNECTION=sqlite DB_DATABASE=:memory: SESSION_DRIVER=array CACHE_STORE=array MAIL_MAILER=array vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/AppointmentWorkflowTest.php`.
- These tests create an isolated SQLite in-memory schema and fake mail/notifications; do not run migrations against the configured application database for verification.
- PHP 8.2+ and Node are needed for these tests. They render the appointment Blade views and check the rendered admin JavaScript with `node --check`.
- Run `php -l` on changed PHP files, `php artisan route:list --path=rendez-vous` for appointment routes, and `git diff --check`.
