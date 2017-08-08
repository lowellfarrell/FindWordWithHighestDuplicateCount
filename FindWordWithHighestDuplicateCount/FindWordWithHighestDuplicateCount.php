<?php

define('ROOT_DIR', dirname(__FILE__) . '/');

require(ROOT_DIR . 'AutoLoader.php');


$logger = new logger\LoggerHelper();

function exception_handler(Exception $exception)
{
    global $logger;
    $message = "FindWordWithHighestDuplicateCount: [" . date("m/d/y H:i:s") . "] Uncaught exception: " . $exception->getMessage() . "\nStack trace:\n" . $exception->getTraceAsString();
    $logger->log($message);
}

function error_handler($errno, $errstr, $errfile, $errline)
{
    global $logger;
    switch ($errno) {
        case 1:
            $e_type = 'E_ERROR';
            break;
        case 2:
            $e_type = 'E_WARNING';
            break;
        case 4:
            $e_type = 'E_PARSE';
            break;
        case 8:
            $e_type = 'E_NOTICE';
            break;
        case 16:
            $e_type = 'E_CORE_ERROR';
            break;
        case 32:
            $e_type = 'E_CORE_WARNING';
            break;
        case 64:
            $e_type = 'E_COMPILE_ERROR';
            break;
        case 128:
            $e_type = 'E_COMPILE_WARNING';
            break;
        case 256:
            $e_type = 'E_USER_ERROR';
            break;
        case 512:
            $e_type = 'E_USER_WARNING';
            break;
        case 1024:
            $e_type = 'E_USER_NOTICE';
            break;
        case 2048:
            $e_type = 'E_STRICT';
            break;
        case 4096:
            $e_type = 'E_RECOVERABLE_ERROR';
            break;
        case 8192:
            $e_type = 'E_DEPRECATED';
            break;
        case 16384:
            $e_type = 'E_USER_DEPRECATED';
            break;
        case 30719:
            $e_type = 'E_ALL';
            break;
        default:
            $e_type = 'E_UNKNOWN';
            break;
    }
    $message = "FindWordWithHighestDuplicateCount: [" . date("m/d/y H:i:s") . "] PHP error '{$e_type}': occured on line {$errline} in the file {$errfile} with the error message of: {$errstr}";
    $logger->log($message);
}

set_exception_handler('exception_handler');
set_error_handler('error_handler');

$inputFile = $argv[1];
$result = '';

if (file_exists($inputFile) && ($handle = fopen($inputFile, "r")) !== false) {
    $text = fread($handle, filesize($inputFile));
    if (fclose($handle) === false) {
        $this->logger->log('The text file handler for "' . $inputFile . '" could not be closed.');
    }

    if ($text !== false) {
        $result = (new detector\DuplicateDector($logger))->findHighestDuplicationPerWordInText($text);
    } else {
        $logger->log('The text file, "' . $inputFile . '", was empty.');
    }
} else {
    $logger->log('The text file, "' . $inputFile . '", could not be read or does not exist.');
}

if($result === ''){
    $logger->log("A word with duplicated letters was not found.");
}
echo $result;
