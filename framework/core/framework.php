<?php

/**
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 11/29/2016
 * Time: 10:54 PM
 */

class Framework
{
    public function __construct()
    {
        // Define root directory.
        define("DS", DIRECTORY_SEPARATOR);
        define("BD", ".."); // Back directory
        define("ROOT", dirname( __FILE__ ) . DS);

        // Define file paths.
        define("APP", ROOT . BD . DS . BD . DS . "application" . DS);
        define("PUB", ROOT . BD . DS . BD . DS . "public" . DS);
        define("FRAMEWORK", ROOT . BD . DS . BD . DS . "framework" . DS);
        define("LOG_PATH", ROOT . BD . DS . BD . DS . "logs" . DS);
        define("IMG_PATH", PUB . "img" . DS);

        define("CONFIG_PATH", APP . "config" . DS);
        define("CONTROLLER_PATH", APP . "controller" . DS);
        define("INCLUDE_PATH", APP . "includes" . DS);
        define("VIEW_PATH", APP . "views" . DS);
        define("MODEL_PATH", APP . "models" . DS);

        define("CORE_PATH", FRAMEWORK . "core" . DS);
        define("LIB_PATH", FRAMEWORK . "lib" . DS);
        define("DB_PATH", FRAMEWORK . "db" . DS);

        /**
         * Include files.
         */
        include_once INCLUDE_PATH . "error_handler.php";

        /**
         * Application files.
         */

        // Config files.
        include_once CONFIG_PATH . "config.php";

        // Include files.

        // Model files.
        include_once MODEL_PATH . "attempts.model.php";

        // Essential controllers.
        include_once CONTROLLER_PATH . "home.controller.php";

        /**
         * Framework files.
         */

        // Core files.

        // Database files.
        include_once DB_PATH . "database.class.php";

        // Library files.
        include_once LIB_PATH . "functions.class.php";
        include_once LIB_PATH . "url_parser.class.php";
        include_once LIB_PATH . "router.class.php";
    }

    /**
     * The method starts the framework.
     */
    public function run()
    {
        // Parse URL request.
        UrlParser::getInstance()->parseUrl();

        // Create the router.
        $router = new Router();

        // Dispatch the url request.
        $router->dispatch();
    }
}