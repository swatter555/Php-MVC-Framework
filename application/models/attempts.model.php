<?php
/**
 * Created by PhpStorm.
 * User: coder
 * Date: 11/22/2016
 * Time: 4:02 AM
 */

class LoginAttemptsModel
{
    /**
     * Get the number of attempts during defined period of time.
     *
     * @param $numAttempts
     * @param $userID
     * @return null|PDOStatement
     */
    public static function checkLoginAttempts($numAttempts, $userID)
    {
        // Get table name.
        $table = Config::get("attempts_table");

        // Define query.
        $query = "SELECT time FROM " . $table .
                 " WHERE user_id = ?" .
                 " AND time > " . $numAttempts;

        // Perform custom query.
        return MySqlPDO::performQuery($query,[$userID]);
    }
}