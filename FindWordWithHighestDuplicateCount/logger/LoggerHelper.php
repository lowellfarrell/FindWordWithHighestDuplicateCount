<?php

namespace logger;

/**
 * Helper class for logging 
 *
 */
class LoggerHelper
{

    /**
     * Path to the log file
     *
     * @access private
     * @var string
     */
    private $logFilePath = null;

    /**
     * process PID for thread instance
     *
     * @access private
     * @var resource
     */
    private $processId = null;

    /**
     * Valid PHP date() format string for log timestamps
     *
     * @access private
     * @var string
     */
    private $messageBuffer = '';

    /**
     * Class constructor
     *
     * @access public
     * @return void
     * @throws RuntimeException             Failed file/folder operations
     */
    public function __construct()
    {
        $this->processId = getmypid();
        $this->logFilePath = ROOT_DIR . 'debug.log';
        $this->log('Starting logging instancance');
    }

    /**
     * Class destructor closes file handle and deletes if no content was written
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        $this->log('Ending logging instancance');
        $this->write();
    }

    /**
     * Logs message and optional context with an arbitrary level.
     *
     * @access public
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return void
     */
    public function log($message)
    {
        $logMessage = $this->formatMessage($message);
        $this->messageBuffer .= $logMessage;
    }

    /**
     * Writes a line to the log without prepending a status or timestamp
     *
     * @access private
     * @param string $message Message string to write to the log
     * @return void
     */
    private function write()
    {
        $fileHandle = \fopen($this->logFilePath, 'a');
        if ($fileHandle === false) {
            \syslog($this->formatMessage('FATAL PHP: The log file, "' . $this->logFilePath . '", could not be opened. Check permissions.'));
        }

        if (fwrite($fileHandle, $this->messageBuffer) === false) {
            \syslog('FATAL PHP: The log file, "' . $this->logFilePath . '",  could not be written to. Check that appropriate permissions have been set.');
        }

        if (\fclose($fileHandle) === false) {
            \syslog('FATAL PHP: The log file handle for, "' . $this->logFilePath . '",  could not be closed. Check that appropriate permissions have been set.');
        }
    }

    /**
     * Formats the message for logging.
     *
     * @access private
     * @param  string $message The message to log
     * @return string
     */
    private function formatMessage($message)
    {
        return "[{$this->getTimestamp()}] [{$this->processId}] {$message}" . \PHP_EOL . " \r\n";
    }

    /**
     * Gets the correctly formatted Date/Time for the log entry.
     *
     * @access private
     * @return string
     */
    private function getTimestamp()
    {
        $originalTime = \microtime(true);
        $micro = \sprintf("%06d", ($originalTime - \floor($originalTime)) * 1000000);
        $date = new \DateTime(date('Y-m-d H:i:s.' . $micro, $originalTime));

        return $date->format('Y-m-d H:i:s.u');
    }

}
