## test university model

Test laravel api application with university model.

## Install & run

Clone this repo, fill `.env` and `.env.testing` then run following commands:
```
composer install
php artisan migrate
php artisan serve
```

Running tests:
```
php artisan migrate --env=testing
php artisan db:seed --env=testing
php artisan test
```

## API docs
### Classes

list
```
GET /api/classes
```

info
```
GET /api/class/{class_id}
```

create
```
POST /api/class

params:
 - name: string, required
```

update
```
PUT /api/class/{class_id}

params:
 - name: string, required
```

delete
```
DELETE /api/class/{class_id}
```

attached lection list
```
GET /api/class/{class_id}/lections
```

set lection list
```
PUT /api/class/{class_id}/lections

params:
 - table: json, required
/*
    example:
    [
        {
            lection_id: 1,
            planned_at: '2022-04-20 12:30:00',
        },
        {
            lection_id: 2,
            planned_at: '2022-04-21 12:30:00',
        },
        {
            lection_id: 3,
            planned_at: '2022-04-22 12:30:00',
        },
    ],
*/
```
### Students
list
```
GET /api/students
```

info
```
GET /api/student/{student_id}
```

create
```
POST /api/student

params:
 - name: string, required
 - email: string, required
 - class_id: int, required
```

update
```
PUT /api/student/{student_id}

params:
 - name: string, required
 - email: string, required
 - class_id: int, required
```

delete
```
DELETE /api/student/{student_id}
```

### Lections
list
```
GET /api/lections
```

info
```
GET /api/lection/{lection_id}
```

create
```
POST /api/lection

params:
 - subject: string, required
 - description: string, required
```

update
```
PUT /api/lection/{lection_id}

params:
 - subject: string, required
 - description: string, required
```

delete
```
DELETE /api/lection/{lection_id}
```
