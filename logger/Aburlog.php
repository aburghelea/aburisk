<?php
class Aburlog
{

    const ERROR = 1;
    const WARN = 2;
    const INFO = 3;
    const DEBUG = 4;

    const OFF = 5;

    const NO_ARGUMENTS = 'Aburlog::NO_ARGUMENTS';

    private $_logFilePath = null;
    private $_severityThreshold = self::INFO;
    private $_fileHandle = null;


    private static $_dateFormat = 'Y-m-d G:i:s';
    private static $_fullPermisions = 0777;


    private static $instance = null;
    private static $instanceDate = null;

    public static function getInstance($logDirectory = false, $severity = self::DEBUG)
    {
        if (self::$instanceDate != date("Y-m-d")) {
            self::$instance = null;
        }
        if (!self::$instance) {
            self::$instance = new self($logDirectory, $severity);
        }

        return self::$instance;
    }

    public function __construct($logDirectory, $severity)
    {
        $logDirectory = $logDirectory == false ? dirname(__FILE__) : $logDirectory ;
        $logDirectory = rtrim($logDirectory, '\\/');

        if ($severity === self::OFF) {
            return;
        }
        self::$instanceDate = date("Y-m-d");
        $this->_logFilePath = $logDirectory . DIRECTORY_SEPARATOR . 'aburisk_' . date('Y-m-d') . '.log';

        $this->_severityThreshold = $severity;
        if (!file_exists($logDirectory)) {
            mkdir($logDirectory, self::$_fullPermisions, true);
        }

        $this->_fileHandle = fopen($this->_logFilePath, 'a');
    }

    public function __destruct()
    {
        if ($this->_fileHandle) {
            fclose($this->_fileHandle);
        }
    }


    public function logDebug($line, $args = self::NO_ARGUMENTS)
    {
        $this->log($line, self::DEBUG, $args);
    }

    public function logInfo($line, $args = self::NO_ARGUMENTS)
    {
        $this->log($line, self::INFO, $args);
    }


    public function logWarn($line, $args = self::NO_ARGUMENTS)
    {
        $this->log($line, self::WARN, $args);
    }


    public function logError($line, $args = self::NO_ARGUMENTS)
    {
        $this->log($line, self::ERROR, $args);
    }

    public function log($line, $severity, $args = self::NO_ARGUMENTS)
    {
        if ($this->_severityThreshold >= $severity) {
            $status = $this->_getTimeLine($severity);

            $line = "$status $line";

            if ($args !== self::NO_ARGUMENTS) {
                $line = $line . ';' . PHP_EOL . var_export($args, true);
            }

            $this->writeLine($line . PHP_EOL);
        }
    }

    public function writeLine($line)
    {
        if ($this->_severityThreshold != self::OFF) {
            if (fwrite($this->_fileHandle, $line) === false) {
                //TODO log failed
            }
        }
    }

    private function _getTimeLine($level)
    {
        $time = date(self::$_dateFormat);

        switch ($level) {
            case self::INFO:
                return "[$time][ABURISK:INFO][" . self::get_client_ip() . "]";
            case self::WARN:
                return "[$time][ABURISK:WARN][" . self::get_client_ip() . "]";
            case self::DEBUG:
                return "[$time][ABURISK:DEBUG][" . self::get_client_ip() . "]";
            case self::ERROR:
                return "[$time][ABURISK:ERROR][" . self::get_client_ip() . "]";
            default:
                return "[$time][ABURISK:LOG][" . self::get_client_ip() . "]";
        }
    }

    public static function get_client_ip()
    {
        if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }
}