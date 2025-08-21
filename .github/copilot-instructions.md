# Copilot Instructions for atour Laravel Project

## Project Overview
- This is a Laravel-based web application for managing suppliers, countries, and related business logic.
- The codebase uses a modular structure: business logic in `app/`, configuration in `config/`, routes in `routes/`, and Blade templates in `resources/views/`.
- Supplier and country management is handled via Eloquent models (`User`, `Supplier`, `Country`) and their respective controllers in `app/Http/Controllers/Admin/`.

## Key Patterns & Conventions
- **Translations:** All static text in Blade views should use translation keys (e.g., `__('suppliers.name')`). Language files are in `resources/lang/` and available languages are configured in `config/languages.php`.
- **Form Handling:** Forms for entities like suppliers and countries use dynamic fields based on config and translation files. See `resources/views/admin/pages/suppliers/create_edit.blade.php` for examples.
- **User/Supplier Relationship:** A `User` of type `supplier` has a related `Supplier` record. Controllers handle both models together (see `SupplierController@store` and `@update`).
- **AJAX Selects:** Country and city selects use AJAX endpoints (e.g., `admin.countries.select`).
- **DataTables:** List endpoints return JSON for Yajra DataTables, with columns often built from related models (see `CountryController@list`).

## Developer Workflows
- **Run the app:** Use `php artisan serve` or Docker (`docker-compose up`).
- **Migrations:** Use `php artisan migrate` for DB setup.
- **Testing:** PHPUnit is configured; run tests with `vendor/bin/phpunit`.
- **Assets:** Use Laravel Mix (`npm run dev` or `npm run prod`) for asset compilation.

## Integration Points
- **Mail:** Uses Laravel's mail system (see `App\Mail\SendPasswordMail`).
- **External Packages:** Yajra DataTables, Intervention Image, and others are used (see `composer.json`).
- **Frontend:** Blade templates, jQuery, and some custom JS for dynamic forms.

## Examples
- To add a new supplier field, update the migration, model `$fillable`, form Blade, and translation files.
- To add a new language, update `config/languages.php` and add translation files in `resources/lang/`.

## References
- Main controllers: `app/Http/Controllers/Admin/`
- Main models: `app/Models/`
- Blade views: `resources/views/admin/pages/`
- Config: `config/`
- Translations: `resources/lang/`

---
If you are unsure about a workflow or pattern, check for examples in the relevant controller or Blade file, or ask for clarification.
