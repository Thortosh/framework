<?php

namespace Anton\Helpers;

class HttpHelper
{
    // Создаем константу с номером и описанием ошибки
    const MESSAGES = [
        404 => '404 Маршрут не найдет!',
        505 => '505 Ничего не найдено',
        405 => '405 Метод запрещен'
    ];

    public static function sendCode($code, $message = null)            // создаем метод
    {
        http_response_code($code);                                      // http_response_code — Получает или устанавливает код ответа HTTP
        return print_r($message ?? self::MESSAGES[$code] ?? 'default message');                 // возвращаем сообщение ошибки если определена, иначе обращаемся к константе, либо выводим default message
    }

    /**
     * @param $url
     * @param array $withErrors = []
     * @return bool
     */
    public static function redirect($url, $withErrors = [])
    {
        if (count($withErrors)) {
            $query = http_build_query(['errors' => $withErrors]);
            $url .= '?' . $query;
        }

        header("Location: $url", true, 303);
        return true;
    }
}