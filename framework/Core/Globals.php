<?php

/**
 * функция конфигурации
 * function_exists — Возвращает TRUE, если указанная функция определена
 * Если функция config не определена, определяем функцию конфиг. В данном примере используем function_exists, что бы не наткнуться на функцию с таким же именем
 * функция config принимает на вход два аргумента:
 * $configpath - путь к конфигурации и $default - по умолначиню null
 * В переменнуж $keyPath записываем переданный аргумент $configpath и спомощью функции explode разбиваем строку с помощью разделителя '.'
 * В переменную $configFilePath записываем CONFIG_PATH(константа которая смотрит в директорию application/config), $keyPath[0](имя файла) и дописываем расширение '.php'.
 * Например если аргумент функции $configpath = 'config' то переменная $configFilePath = application/config/config.php
 * Если такого файла не существует функция вернет дефолтное значение, второй аргумент, который по умолчанию null
 * После того как получили имя файла, удаляем этот ключ из массива.
 * Записываем в переменную $configFilePath подключае файл $configFilePath(который является массивом)
 * Проверяем ключи массива $keyPath, если в массиве $file отсутствует ключ $keyPath, возващаем null
 * иначе $file = $file[$key]
 * Возвращаем содержимое $file
 */
if (!function_exists('config')) {
    function config($configpath, $default = null)
    {
        $keyPath = (explode('.', $configpath));
        $configFilePath = CONFIG_PATH . $keyPath[0] . '.php';
        if (!file_exists($configFilePath)) {
            return $default;
        }
        unset($keyPath[0]);
        $file = require $configFilePath;

        foreach ($keyPath as $key) {
            if (!array_key_exists($key, $file)) {
                return $default;
            }
            $file = $file[$key];
        }

        return $file;
    }
}
