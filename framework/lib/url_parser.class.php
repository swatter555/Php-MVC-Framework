<?php

/**
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 11/29/2016
 * Time: 11:36 PM
 */

class UrlParser
{
    /**
     * @var UrlParser The reference to *Singleton* instance of this class
     */
    private static $instance;

    // Non-static members.
    private $host;
    private $url;
    private $urlParts;
    private $path;
    private $path_parts;
    private $query;
    private $queryParts;
    private $controller = DEFAULT_CONTROLLER;
    private $action = DEFAULT_ACTION;

    /**
     * Returns the *UrlParser* instance of this class.
     *
     * @return UrlParser The *Singleton* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance)
        {
            // Create the static instance.
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function __construct()
    {
    }

    /**
     * Parse the URL into individual parts.
     */
    public function parseUrl()
    {
        // Get requested URL.
        $requestedUrl = "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        // Save the host.
        $this->host = $_SERVER['HTTP_HOST'];

        // Save escaped URL.
        $this->url = htmlspecialchars( $requestedUrl, ENT_QUOTES, 'UTF-8' );

        // Break url into parts.
        $this->urlParts = parse_url($this->url);

        // Set path parts.
        if (isset($this->urlParts["path"]))
        {
            $this->path = $this->urlParts["path"];
        }
        else
        {
            $this->path = $_SERVER['HTTP_HOST'];
        }

        // Set query parts.
        if (isset($this->urlParts["query"]))
        {
            $this->query = $this->urlParts["query"];
        }

        // Separate path into parts.
        $this->path_parts = explode('/', $this->path);

        // Separate query into parts.
        parse_str($this->query, $this->queryParts);

        // Get the controller from the path.
        $this->parseController();
    }

    /**
     * Look for the controller in the url path.
     */
    private function parseController()
    {
        // Get path array.
        $pathArray = $this->getPathParts();

        // Go through the array to look for a valid controller.
        foreach ($pathArray as $value)
        {
            // Construct controller path.
            $path = CONTROLLER_PATH . $value . ".controller.php";

            // If that path exists, there is your controller.
            if (file_exists($path))
            {
                $this->controller = $value;
                return;
            }
        }
    }

    /**
     * Look for the action in url path.
     */
    public function parseAction()
    {
        // Get path array.
        $queries = $this->getQueryParts();

        // Get class name from controller.
        $className = ucfirst($this->controller);
        $className .= "Controller";

        // Check each path part for a valid action.
        foreach ($queries as $value)
        {
            if (method_exists($className, $value))
            {
                $this->action = $value;
                return;
            }
        }
    }

    /**
     * Render complete URL information.
     */
    public function renderUrlInfo()
    {
        print ("<br>");
        print ("Host: " . $this->host);
        print ("<br>");
        print ("URL: " . $this->url);
        print("<br>");
        print_r($this->urlParts);
        print("<br>");
        print("Path: " . $this->path);
        print("<br>");
        print_r($this->path_parts);
        print("<br>");
        print ("Query: " . $this->query);
        print("<br>");
        print_r($this->queryParts);
        print ("<br>");
        print ("Controller: " . $this->controller);
        print ("<br>");
        print ("Action: " . $this->action);
        print ("<br>");
        print ("<br>");
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getUrlParts()
    {
        return $this->urlParts;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return array
     */
    public function getPathParts()
    {
        return $this->path_parts;
    }

    /**
     * @return mixed
     */
    public function getQueryParts()
    {
        return $this->queryParts;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}