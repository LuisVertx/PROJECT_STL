# Slot Booking Service

Сервис управления бронированием слотов.

## Стек

- PHP 8.2
- Laravel 12
- MySQL 8
- Laravel Cache

---

## Установка


git clone <repository>

cd project_stl

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve


---

## API


## Создание слота

Для упрощения тестирования можно создать слот напрямую через базу данных или с помощью Laravel Tinker.

Через Laravel Tinker

Запустите:

php artisan tinker

Создайте слот:

use App\Models\Slot;
Slot::create([
    'capacity' => 20,
    'remaining' => 20,
]);

Проверить созданные слоты:

Slot::all();



После создания слота его можно использовать в API. Например, если был создан слот с id = 1, доступны следующие запросы:

* GET /api/slots/availability
* POST /api/slots/1/hold
* POST /api/holds/1/confirm
* DELETE /api/holds/1

### Получение доступных слотов


GET /api/slots/availability


Ответ:


[
    {
        "slot_id":1,
        "capacity":10,
        "remaining":8
    }
]


---

### Создание hold


POST /api/slots/1/hold


Заголовок


Idempotency-Key:
550e8400-e29b-41d4-a716-446655440000


---

### Подтверждение


POST /api/holds/1/confirm


---

### Отмена


DELETE /api/holds/1


---

## Особенности реализации

- используется транзакция;
- используется lockForUpdate();
- реализована идемпотентность;
- кеш инвалидируется после confirm/cancel;
- реализована защита от race condition.


## Примеры

Получить список слотов


curl.exe http://127.0.0.1:8000/api/slots/availability


Создать hold


curl.exe -X POST -H "Idempotency-Key: 550e8400-e29b-41d4-a716-446655440000" http://127.0.0.1:8000/api/slots/1/hold


Повторить запрос


curl.exe -X POST -H "Idempotency-Key: 550e8400-e29b-41d4-a716-446655440000" http://127.0.0.1:8000/api/slots/1/hold


Подтвердить


curl.exe -X POST http://127.0.0.1:8000/api/holds/1/confirm


Отменить


curl.exe -X DELETE http://127.0.0.1:8000/api/holds/1
