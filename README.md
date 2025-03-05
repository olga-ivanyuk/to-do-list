## Google Calendar Integration Setup

1. У Google Cloud створіть сервісний акаунт і завантажте `service-account.json`.
2. Помістіть цей файл у `storage/app/google-calendar/service-account.json`.
3. В `.env` вкажіть шлях:
4. Виконайте `php artisan storage:link`, якщо потрібно зробити файли доступними для публічного доступу (не потрібно для Google Calendar).
5. Переконайтеся, що файл існує, запустивши:
```php
dd(storage_path('app/google-calendar/service-account.json'));
