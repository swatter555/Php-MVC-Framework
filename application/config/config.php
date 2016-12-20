<?php
/**
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 11/8/2016
 * Time: 8:05 AM
 */

// General settings
if (!defined('LIVE')) define('LIVE', false);
define("SITE_NAME", "Your Site Name");
define("DEFAULT_CONTROLLER", "home");
define("DEFAULT_ACTION", "home");
define("CONTACT_EMAIL", "coderbob0101@hotmail.com");
define("CONTACT_EMAIL_FROM", "admin@MyWebsite.com");

// Database settings
define("HOST", "localhost");
define("USER", "sec_user");
define("PASSWORD", "ecr7cnEjLj3vm4Y5");
define("DATABASE", "secure_login");
define("PORT", "3306");
define("CHARSET", "utf8");

// Login table names.
define("MEMBERS_TABLE", "members");
define("ATTEMPTS_TABLE", "login_attempts");

// Log file directories
define("LOGS_ACTIVE", true);
define("SQL_LOG_PATH", LOG_PATH . "sql_log.txt");