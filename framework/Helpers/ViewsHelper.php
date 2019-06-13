<?php

namespace Anton\Helpers;

class ViewsHelper
{
    public static function import($template)
    {
        $templateName = VIEW_PATH . str_replace('.', DS, $template) . '.php';

        if (!file_exists($templateName)) {
            return 'template not found';
        }
        include $templateName;
    }
}