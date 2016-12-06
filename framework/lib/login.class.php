<?php
/**
 * Created by PhpStorm.
 * User: Robert Lohaus
 * Date: 11/12/2016
 * Time: 3:03 AM
 */

class LoginHelpers
{
    /**
     * @var LoginHelpers The reference to *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *LoginHelpers* instance of this class.
     *
     * @return LoginHelpers The *Login* instance.
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
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }

    /**
     * Secure login function.
     */
    public function login($email, $password)
    {
        // Run SQL statement and store the PDO result.
        $stmt = MySqlPDO::SELECT(["id","username","password"],
                                  MEMBERS_TABLE,
                                  "email","=",[$email],1);

        // Bind some columns for local use.
        $stmt->bindColumn(1, $userId);
        $stmt->bindColumn(2, $username);
        $stmt->bindColumn(3, $db_password);

        if ($stmt->rowCount() == 1)
        {
            /**
             * If the user exists we check if the account is locked
             * from too many login attempts.
             */
            if (self::checkbrute($userId) == true)
            {
                /**
                 * Account is locked. Send an email to user saying
                 * TODO-general implement email service
                 */
                return false;
            }
            /**
             * Check if the password in the database matches
             * the password the user submitted. We are using
             * the password_verify function to avoid timing attacks.
             */
            elseif (password_verify($password, $db_password))
            {
                /** Password is correct!
                 * Get the user-agent string of the user.
                 */
                $user_browser = $_SERVER['HTTP_USER_AGENT'];

                // XSS protection as we might print this value
                $userId = preg_replace("/[^0-9]+/", "", $userId);
                $_SESSION['user_id'] = $userId;

                // XSS protection as we might print this value
                $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

                // Set session username and login string.
                $_SESSION['username'] = $username;
                $_SESSION['login_string'] = hash('sha512', $db_password . $user_browser);

                // Login successful.
                return true;
            }
            else
            {
                /**
                 * Password is not correct so we record
                 * this attempt in the database.
                 */
                $now = time();

                // Add attempt to table.
                MySqlPDO::INSERT([$userId,$now], ATTEMPTS_TABLE);

                return false;
            }
        }

        // User doesn't exist.
        return false;
    }

    /**
     * Checks for brute force login attempts.
     */
    public function checkbrute($user_id)
    {
        // Get timestamp of current time.
        $now = time();

        // All login attempts are counted from the past 2 hours.
        $valid_attempts = $now - (2 * 60 * 60);

        // Get PDO statement.
        $stmt = LoginAttemptsModel::checkLoginAttempts($valid_attempts, $user_id);

        // Check for 5 or more attempts.
        if ($stmt->rowCount() > 5)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Checks whether user is logged in.
     *
     * @return bool
     */
    public function loginCheck()
    {
        // Check if all session variables are set
        if (isset($_SESSION['user_id'],
            $_SESSION['username'],
            $_SESSION['login_string']))
        {

            $user_id = $_SESSION['user_id'];
            $login_string = $_SESSION['login_string'];

            // Get the user-agent string of the user.
            $user_browser = $_SERVER['HTTP_USER_AGENT'];

            // Get PDO statement.
            $stmt = MySqlPDO::SELECT("password",MEMBERS_TABLE,"id","=",[$user_id],1);

            if ($stmt->rowCount() == 1)
            {
                // If the user exists get variables from result.
                $stmt->bindColumn(1, $password);
                $login_check = hash('sha512', $password . $user_browser);

                if (hash_equals($login_check, $login_string) )
                {
                    // Logged In!!!!
                    return true;
                }
                else
                {
                    // Not logged in.
                    return false;
                }
            }
            else
            {
                // Not logged in.
                return false;
            }
        }
        else
        {
            // Not logged in.
            return false;
        }
    }

    /**
     * Starts more secure session.
     */
    public function secureSessionStart()
    {
        // Create custom name and set session.
        $session_name = 'sec_session_id';
        session_name($session_name);

        // This stops JavaScript being able to access the session id.
        $secure = true;
        $httponly = true;

        // Forces sessions to only use cookies.
        if (ini_set('session.use_only_cookies', 1) === FALSE)
        {
            Functions::getInstance()->renderErrorPage(GENERIC_ERROR);
        }

        // Gets current cookies params.
        $cookieParams = session_get_cookie_params();
        session_set_cookie_params($cookieParams["lifetime"],
            $cookieParams["path"],
            $cookieParams["domain"],
            $secure,
            $httponly);

        // Start the PHP session.
        session_start();

        // Regenerated the session, delete the old one.
        session_regenerate_id(true);
    }

    /**
     * This function sanitizes the output from the PHP_SELF server variable.
     *
     */
    public function esc_url($url)
    {
        if ('' == $url)
        {
            return $url;
        }

        $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

        $strip = array('%0d', '%0a', '%0D', '%0A');
        $url = (string) $url;

        $count = 1;
        while ($count)
        {
            $url = str_replace($strip, '', $url, $count);
        }

        $url = str_replace(';//', '://', $url);

        $url = htmlentities($url);

        $url = str_replace('&amp;', '&#038;', $url);
        $url = str_replace("'", '&#039;', $url);

        if ($url[0] !== '/')
        {
            // We're only interested in relative links from $_SERVER['PHP_SELF']
            return '';
        }
        else
        {
            return $url;
        }
    }

    /**
     * Logout the user.
     */
    public function logout()
    {
        // Unset all session values
        $_SESSION = array();

        // Get session parameters
        $params = session_get_cookie_params();

        // Delete the actual cookie.
        setcookie(session_name(),
            '', time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]);

        // Destroy session
        session_destroy();

        // Redirect to homepage.
        Functions::getInstance()->redirect(UrlParser::getInstance()->getHost());
    }
}