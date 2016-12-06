<?php
/**
 * Created by PhpStorm.
 * User: coder
 * Date: 12/6/2016
 * Time: 3:00 AM
 */

class ErrorController
{
    private $title = "Error Page";
    private $message = "Sorry, an error has occurred.";

    /**
     * Display the error page with proper title and message
     * for given error code.
     *
     * @param $errorCode
     */
    public function error_page($errorCode)
    {
        switch ($errorCode) {
            case 0:
                $this->title = "Error Page";
                $this->message = "Sorry, an error has occurred.";
                break;

            case 1:
                $this->title = "Bad Request";
                $this->message = "Sorry, the requested page doesn't exist.";
                break;
        }

        // Render the page.
        self::renderPage($this->title, $this->message);
    }

    /**
     * Render the error page.
     *
     * @param $title
     * @param $message
     */
    private function renderPage($title, $message)
    {
        // Display error page.
        require VIEW_PATH . "error.php";

        // Stop all scripts.
        exit();
    }
}