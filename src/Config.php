<?php

declare(strict_types=1);

enum Config
{
    /** Доступные web/cli роуты приложения */
    case ACTIONS_LIST;

    /** Дефолтная команда для CLI */
    case ACTION_DEFAULT_CLI;

    /** Дефолтная команда для WEB */
    case ACTION_DEFAULT_HTTP;

    /** Параметры для подключения к БД */
    case ORM_DSN;

    /** Параметры для подключения к службе очередей */
    case QUEUE_DSN;

    /** Список обработчиков заданий из очереди */
    case QUEUE_SUBSCRIBERS;

    /** Поток для вывода информации */
    case OUTPUT;

    /** Соль для хеширования кодов подтверждения */
    case SALT;
}
