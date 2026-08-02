# Slot Booking Service

Сервис управления бронированием слотов.

## Стек

- PHP 8.2
- Laravel 12
- MySQL 8
- Laravel Cache

---

## Установка

```bash
git clone <repository>

cd project_stl

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

---

## API

### Получение доступных слотов

```http
GET /api/slots/availability
```

Ответ:

```json
[
    {
        "slot_id":1,
        "capacity":10,
        "remaining":8
    }
]
```

---

### Создание hold

```http
POST /api/slots/1/hold
```

Заголовок

```
Idempotency-Key:
550e8400-e29b-41d4-a716-446655440000
```

---

### Подтверждение

```http
POST /api/holds/1/confirm
```

---

### Отмена

```http
DELETE /api/holds/1
```

---

## Особенности реализации

- используется транзакция;
- используется lockForUpdate();
- реализована идемпотентность;
- кеш инвалидируется после confirm/cancel;
- реализована защита от race condition.


## Примеры

Получить список слотов

```bash
curl.exe http://127.0.0.1:8000/api/slots/availability
```

Создать hold

```bash
curl.exe -X POST ^
-H "Idempotency-Key: 550e8400-e29b-41d4-a716-446655440000" ^
http://127.0.0.1:8000/api/slots/1/hold
```

Повторить запрос

```bash
curl.exe -X POST ^
-H "Idempotency-Key: 550e8400-e29b-41d4-a716-446655440000" ^
http://127.0.0.1:8000/api/slots/1/hold
```

Подтвердить

```bash
curl.exe -X POST ^
http://127.0.0.1:8000/api/holds/1/confirm
```

Отменить

```bash
curl.exe -X DELETE ^
http://127.0.0.1:8000/api/holds/1
```