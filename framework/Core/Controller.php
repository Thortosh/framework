<?php

namespace Anton\Core;


class Controller
{
    /**
     * @param $template
     * @param array $params
     * @return string
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
