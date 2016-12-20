<?php
/**
 * Helper functions singleton.
 *
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 11/8/2016
 * Time: 8:14 AM
 */

class Functions
{
    /**
     * @var Functions The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Functions* instance of this class.
     *
     * @return Functions The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance)
        {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Renders template, passing in values.
     * @param $template
     * @param array $values
     */
    public function render($view, $values = [])
    {
        // Render template if it exists
        if (file_exists($view))
        {
            // Extract variables into local scope.
            extract($values);

            // Render header
            require(INCLUDE_PATH . "header.php");

            // Render template
            require("$view");

            // Render footer
            require(INCLUDE_PATH . "footer.php");
        }
        else
        {
            trigger_error("The requested page doesn't exist.");
        }
    }

    /**
     * Write a message to a log file.
     *
     * @param $logFile
     * @param $message
     */
    public function writeToLog($logFile, $message)
    {
        if (LOGS_ACTIVE)
        {
            $message .= "  [". date("Y-m-d H:i:s") ."]" . PHP_EOL;
            file_put_contents($logFile, $message, FILE_APPEND);
        }
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     *
     * @param $destination
     */
    public function redirect($destination)
    {
        // Handle URL.
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // Handle absolute path.
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // Handle relative path.
        else
        {
            // Adapted from http://www.php.net/header.
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // Exit immediately since we're redirecting anyway.
        exit;
    }
}