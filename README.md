# To-Do List App

## Opis

To-Do List App — jest internetową aplikacją do zarządzania zadaniami, która umożliwia użytkownikom dodawanie, edycję, przeglądanie i usuwanie zadań. Aplikacja zawiera funkcje filtrowania, powiadomienia e-mail na dzień przed terminem i integrację z Kalendarzem Google.

## Інсталяція

Aby skonfigurować ten projekt na swojej lokalnej maszynie, wykonaj następujące kroki:

1. Sklonuj repozytorium:
   git clone https://github.com/olga-ivanyuk/to-do-list
2. Przejdź do katalogu projektu:
   cd to-do-list
3. Zainstaluj zależności za pomocą Composera:
   composer install
4. Skopiuj plik `.env.example` do `.env`:
   cp .env.example .env
5. Wygeneruj klucz aplikacji:
   php artisan key:generate
6. Skonfiguruj bazę danych. Określ parametry połączenia w pliku .env
7. Uruchom migracje, aby utworzyć tabele w bazie danych:
   php artisan migrate
8. Uruchom serwer:
   php artisan serve

Teraz możesz otworzyć aplikację w przeglądarce pod adresem http://localhost:8000

### 4. Używanie
1. Przejdź do strony logowania pod adresem `http://localhost:8000/login`.
2. Zarejestruj się, aby rozpocząć korzystanie z aplikacji.
3. Przejdź do sekcji Zadania.
4. Dodaj nowe zadanie poprzez formularz na stronie głównej.
5. Edytuj, usuwaj i przeglądaj zadania za pośrednictwem interfejsu.
6. Otrzymaj powiadomienie e-mailem na dzień przed terminem.

### 5. Technologie

Ta aplikacja wykorzystuje następujące technologie i biblioteki:

- **Laravel** — PHP-framework do tworzenia aplikacji
- **MySQL / SQLite** — do pracy z bazą danych
- **spatie/laravel-google-calendar** — do integracji kalendarza Google
- **Mail (Laravel Mailables)** — do wysyłania e-maili
- **Queues (Laravel Queues)** — do przetwarzania powiadomień
- **Laravel Scheduler** — do planowania zadań


### 7. Додаткові інструкції
```markdown
## Dodatkowe instrukcje

- Aby wysyłać wiadomości e-mail, należy skonfigurować serwer SMTP w pliku`.env`.
- Aby skonfigurować Kalendarz Google, musisz utworzyć projekt w Konsoli programistów Google i uzyskać klucze API, a następnie określić je w`.env`.

## Ustawienia poczty Integration Setup

1. Aby wysyłać wiadomości e-mail, należy skonfigurować serwer SMTP w pliku .env. Przykład konfiguracji:

        MAIL_MAILER=smtp
        MAIL_HOST=sandbox.smtp.mailtrap.io
        MAIL_PORT=2525
        MAIL_USERNAME=******
        MAIL_PASSWORD=******
        MAIL_ENCRYPTION=tls
        MAIL_FROM_ADDRESS="to-do-list@gmail.com"
        MAIL_FROM_NAME="${APP_NAME}"

## Google Calendar Integration Setup

1. Zainstaluj pakiet spatie/laravel-google-calendar.
2. W usłudze Google Cloud utwórz konto usługi i pobierz `service-account.json`.
3. Umieść ten plik w `storage/app/google-calendar/service-account.json`.
4. W `.env` podaj ścieżkę
5. Sprawdź, czy plik istnieje, uruchamiając:
```php
dd(storage_path('app/google-calendar/service-account.json'));

## Harmonogram zadań
1. Aby Laravel Scheduler działał poprawnie, należy uruchomić dwie główne komendy w terminalu:

Uruchomienie harmonogramu zadań:
Ta komenda odpowiada za wykonanie wszystkich zaplanowanych zadań, takich jak wysyłanie powiadomień lub inne zadania, które mają określony czas wykonania.
Użyj tej komendy, aby uruchomić harmonogram:
         php artisan schedule:work

2.Uruchamianie programu obsługi kolejki:
Laravel wykorzystuje kolejki do asynchronicznego przetwarzania zadań, np. wysyłania powiadomień. Aby przetworzyć te kolejki, należy uruchomić polecenie:
         php artisan queue:work



