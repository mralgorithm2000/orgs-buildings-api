# REST API Справочник для Организаций, Зданий и Деятельностей

## Содержание

1. [Обзор](#Обзор)
2. [Возможности](#Возможности)
3. [Требования](#Требования)
4. [Установка и настройка](#Установка-и-настройка)
5. [Документация API](#Документация-API)
6. [Аутентификация](#Аутентификация)
7. [Шаблоны проектирования и лучшие практики](#Шаблоны-проектирования-и-лучшие-практики)
8. [Развертывание](#Развертывание)
9. [Тестирование](#Тестирование)
10. [Дополнительные замечания](#Дополнительные-замечания)
11. [Лицензия](#Лицензия)
---

## Обзор

Этот проект представляет собой REST API приложение для управления справочником организаций, зданий и видов деятельности. API поддерживает операции, такие как получение организаций по зданию или виду деятельности, запрос организаций в географических областях и многое другое. Приложение построено с использованием Laravel и следует современным шаблонам проектирования и лучшим практикам, включая шаблон проектирования Repository для четкого разделения кода.

---

## Возможности

- Получение всех организаций в конкретном здании.
  
- Получение всех организаций, связанных с определенным видом деятельности.

- Получение организаций в радиусе или прямоугольной области относительно точки на карте.

- Список всех зданий.

- Получение подробной информации об организации по её ID.

- Поиск организаций по виду деятельности, включая иерархический поиск.

- Поиск организаций по названию.

- Ограничение глубины иерархии видов деятельности до трёх уровней.

---

## Требования

- Docker с Laravel Sail

- PHP 8.1+

- Composer

## Установка и настройка


1. **Клонирование репозитория**:
   ```bash
   git clone https://github.com/mralgorithm2000/orgs-buildings-api.git
   cd orgs-buildings-api
   ```

2. **Установка зависимостей**:
    Запустите следующую команду для установки всех зависимостей:
    ```bash
    composer install
    ```

3. **Настройка файла окружения**:
    Скопируйте пример файла .env.example в .env:
    ```bash
    cp .env.example .env
    ```
4. **Запуск приложения**:
    Запустите сервер с помощью Laravel Sail:
    ```bash
    ./vendor/bin/sail up
    ```

5. **Генерация API-ключа**
    Создайте API-ключ и сохраните его в файле .env под параметром STATIC_API_KEY:
    ```bash
   ./vendor/bin/sail artisan generate:apikey
    ```

    API-ключ будет использоваться для аутентификации и должен быть включен в заголовок X-API-KEY каждого запроса.

6. **Запуск миграций и заполнение базы данных**:
    Запустите следующие команды для настройки базы данных и заполнения её тестовыми данными:
    ```bash
   ./vendor/bin/sail artisan migrate --seed
    ```
7. **Проверка настройки**:
    Запустите тестовый набор, чтобы убедиться, что всё работает корректно:    
    
    ```bash
   ./vendor/bin/sail artisan test
    ```

## Документация API

Документация API доступна по адресу /api/documentation. Посетите этот URL после запуска сервера, чтобы изучить методы API, параметры и ожидаемые ответы.

## Аутентификация

API использует статический API-ключ для аутентификации. Чтобы отправить аутентифицированный запрос, включите заголовок X-API-KEY в ваш запрос со значением, установленным в шаге 5.

Пример:

   ```Header
    GET /api/organizations/building/{building_id} HTTP/1.1
    Host: your-domain.com
    X-API-KEY: your-static-api-key
   ```

## Шаблоны проектирования и лучшие практики

Этот проект реализует следующее:

- **Шаблон проектирования Repository:** Для разделения уровня данных и бизнес-логики, что обеспечивает более чистый и поддерживаемый код.

- **Валидация и обработка ошибок:** Обеспечивает надежность и удобные для пользователя ответы.

- **Dockerизация с Laravel Sail:** Упрощает развертывание и локальную разработку.


## Развертывание

    Для развертывания сервера просто выполните следующую команду:

    ```bash
        ./vendor/bin/sail up
    ```
    
    Это запустит все необходимые сервисы (например, MySQL, PHP, Nginx) в Docker-контейнерах.


## Тестирование

    Убедитесь, что все функции работают корректно, запустив:

    ```bash
        ./vendor/bin/sail artisan test
    ```

## Дополнительные замечания

 - Используйте команду sail для всех задач Artisan, таких как миграции, заполнение базы данных и генерация API-ключей.

 - Тестовые данные включают предварительно заполненные здания, виды деятельности и организации для немедленного использования.

## Лицензия

Этот проект является открытым программным обеспечением и доступен в соответствии с MIT License.