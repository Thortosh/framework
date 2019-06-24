<?php

namespace Anton\Core;

/**
 * Class Controller
 * @package Anton\Core
 */
class Controller
{
    /**
     * @param $template
     * @param array $params
     * @return string
     * Метод render принимает на вход два аргумента $template(шаблон) и $params массив
     * В переменную $tamplateName = VIEW_PATH . str_replace('.', DS, $template) . '.php'; Например метод render передали "login" D:\php\OSPanel\domains\mediasoft\application\views\ . account\login . .php
     * Проверяем есть ли такой файл, если нет возвращаем 'template not found'.
     * В переменную $errors записываем ошибки
     * Если были ошибки записываем их в массив ошибок $params
     * ob_start — Включение буферизации вывода
     * extract — Импортирует переменные из массива в текущую таблицу символов
     * подключаем переданный шаблон
     * ob_get_clean — Получить содержимое текущего буфера и удалить его
     */
    protected function render($template, array $params)
    {
        $templateName = VIEW_PATH . str_replace('.', DS, $template) . '.php';
        if (!file_exists($templateName)) {
            return 'template not found';
        }

        $errors = Request::query('errors', []);
        if (count($errors)) {
            $params['errors'] = $errors;
        }
        ob_start();
        extract($params);
        require $templateName;
        echo ob_get_clean();
    }
}
