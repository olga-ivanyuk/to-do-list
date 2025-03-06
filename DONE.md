# To-Do List App - DONE

## Zakończone zadania:

### 1. Funkcjonalności
- ✔️ Zrealizowano pełne operacje CRUD na zadaniach.
- ✔️ Dodano możliwość filtrowania zadań po priorytecie, statusie i terminie wykonania.
- ✔️ Dodano powiadomienia e-mail, które są wysyłane na dzień przed terminem wykonania zadania.
- ✔️ Stworzono system autentykacji użytkowników w oparciu o Laravel.
- ✔️ Umożliwiono użytkownikom tworzenie publicznych linków do zadań za pomocą tokenów dostępu, które mają ograniczony czas ważności.
- ✔️ Zrealizowano zapisywanie historii zmian zadań (w tym zmiany nazw, statusów, terminów itp.).

### 2. Technologie
- ✔️ Użyto Laravel jako frameworka PHP.
- ✔️ Zintegrowano aplikację z bazą danych SQLite (możliwość przełączenia na MySQL).
- ✔️ Zrealizowano integrację z Google Calendar za pomocą biblioteki `spatie/laravel-google-calendar`.
- ✔️ Powiadomienia e-mailowe realizowane za pomocą systemu Mailables w Laravel.
- ✔️ Zastosowano kolejki do obsługi powiadomień oraz harmonogramowanie zadań za pomocą Laravel Scheduler.

### 3. Wdrożenie i konfiguracja
- ✔️ Aplikacja została wdrożona lokalnie, uruchomiono migracje i konfigurację bazy danych.
- ✔️ Skonfigurowano serwer SMTP do wysyłania e-maili.
- ✔️ Skonfigurowano integrację z Google Calendar, aby umożliwić dodawanie zadań do kalendarza Google.
- ✔️ W aplikacji zaimplementowano funkcjonalność planowania zadań przy użyciu Laravel Scheduler.

### 4. Dokumentacja
- ✔️ Zaktualizowano dokumentację w pliku `README.md`, opisującą sposób instalacji, konfiguracji i uruchomienia projektu.
- ✔️ Dodano instrukcje konfiguracyjne dotyczące integracji z Google Calendar oraz ustawień serwera SMTP w pliku `.env`.

## Wnioski
Aplikacja działa zgodnie z wymaganiami określonymi w specyfikacji. Wszystkie główne funkcjonalności zostały zaimplementowane.
Zostały również zrealizowane dodatkowe funkcje, takie jak zapisywanie historii zmian zadań i integracja z Google Calendar.
Istnieje możliwość dalszego rozwoju aplikacji poprzez dodanie nowych funkcji, jak wsparcie dla podzadań, eksport danych do CSV czy możliwość zmiany powiadomień e-mailowych przez użytkowników.

