<?php

if (!defined('ROOT_DIR')) {
    $environmentTmp = substr(__DIR__, strpos(__DIR__, 'FindFirstDuplicate'));
    $environmentName = substr($environmentTmp, 0, strpos($environmentTmp, DIRECTORY_SEPARATOR));
    $environmentPath = substr(__DIR__, 0, strpos(__DIR__, $environmentName) + strlen($environmentName));
    define('ROOT_DIR', $environmentPath);
}

/**
 * This AutoLoader will dynamicly find any class the is in a file with the class 
 * name as the file name.  The file must be namespaced so that the file path from 
 * the package root directory matches the namespace.
 * 
 */
class AutoLoader
{

    /**
     * This function is for dynamically finding OOP items via namespace.
     * 
     * @param type $className This is the class name plus it's namespace prefix.
     * @return boolean If the function can find the class in quesiton <b>TRUE</B> 
     * will be returned alse <b>FALSE</b> will be returned.
     */
    static public function load($className)
    {
        $filename = ROOT_DIR . DIRECTORY_SEPARATOR . str_replace("\\", DIRECTORY_SEPARATOR, $className) . ".php";
        if (file_exists($filename)) {
            include($filename);
            if (class_exists($className)) {
                return TRUE;
            }
        }
        return FALSE;
    }

}

spl_autoload_register('AutoLoader::load');
