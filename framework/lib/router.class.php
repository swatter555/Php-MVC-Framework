<?php

/**
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 11/29/2016
 * Time: 11:50 PM
 *
 * The is the router that dispatches URL requests to the proper
 * controller and calls the requested action. It is important to
 * not that the UrlParser class automatically verifies if
 * requested controllers and actions exist, so there is no
 * need to check here.
 */

class Router
{
    private $controller;

    /**
     * Dispatch url requests.
     */
    public function dispatch()
    {
        // Call the appropriate controller.
        if (UrlParser::getInstance()->getController() == DEFAULT_CONTROLLER)
        {
            $homeController = new HomeController();
            $homeController->home();
        }
        else
        {
            $this->createController();
            $this->callAction();
        }
    }

    /**
     * This method creates the requested controller.
     */
    public function createController()
    {
        // Get the controller name requested.
        $controllerName = UrlParser::getInstance()->getController();

        // Require the proper controller.
        require CONTROLLER_PATH . $controllerName . ".controller.php";

        // Create the proper class name.
        $className = ucfirst($controllerName);
        $className .= "Controller";

        // Create the controller.
        $this->controller =  new $className();
    }

    /**
     * This method calls requested action.
     */
    public function callAction()
    {
        // We can parse the action after the controller is created.
        UrlParser::getInstance()->parseAction();

        // Get the action name.
        $action = UrlParser::getInstance()->getAction();

        // We need to do one last check to make sure
        // the requested action is appropriate for
        // the requested controller. If we have gotten
        // this far and still have default action there
        // is an error.
        if ($action == DEFAULT_ACTION)
        {
            Functions::getInstance()->renderErrorPage(BAD_REQUEST);
        }

        // Call the action.
        $this->controller->$action();
    }
}