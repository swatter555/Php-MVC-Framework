<?php
/**
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 12/1/2016
 * Time: 3:18 AM
 *
 * Controller that handles displaying error page.
 */

class Errorhandler
{
    private $title = "Error Page";
    private $message = "Sorry, an error has occurred.";

    /**
     * Display the error page with proper title and message
     * for given error code.
     *
     * @param $errorCode
     */
    public function display_error($errorCode)
    {
        switch ($errorCode)
        {
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
        require VIEW_PATH . "display_error.php";

        // Stop all scripts.
        exit();
    }
}