<?php
/**
 * Created by PhpStorm.
 * User: coder
 * Date: 11/29/2016
 * Time: 11:38 PM
 */

class MySqlPDO
{
    /**
     * @var PDO The reference to *PDO* instance of this class
     */
    private static $instance;

    /**
     * @return PDO The *PDO* instance.
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            // Set connection settings.
            $host = HOST;
            $user = USER;
            $password = PASSWORD;
            $dbname = DATABASE;
            $charset = CHARSET;

            // Data source name.
            $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

            // PDO options.
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                static::$instance = new PDO($dsn, $user, $password, $opt);
            } catch (PDOException $e) {
                Functions::getInstance()->writeToLog(ERROR_LOG_PATH, $e->getMessage());
                Functions::getInstance()->renderErrorPage(GENERIC_ERROR);
            }
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
     * Close the PDO connection.
     */
    public static function closeConnection()
    {
        static::$instance = null;
    }

    /**
     * Perform a custom query.
     *
     * @param $query
     * @param $values
     * @return null|PDOStatement
     */
    public static function performQuery($query, $values = [])
    {
        // Prepare and execute the PDO SQL Query.
        $stmt = null;
        try {
            // Prepare the statement.
            $stmt = self::getInstance()->prepare($query);

            // Execute the statement.
            $stmt->execute($values);

        } catch (PDOException $e) {
            Functions::getInstance()->writeToLog(ERROR_LOG_PATH, $e->getMessage());
            Functions::getInstance()->renderErrorPage(GENERIC_ERROR);
        }

        return $stmt;
    }

    /**
     * Execute SELECT statement with PDO.
     *
     * @param $columnName
     * @param $from
     * @param null $where
     * @param null $operator
     * @param array $keyword
     * @return null|PDOStatement
     */
    public static function SELECT($columnName,
                                  $from,
                                  $where = null,
                                  $operator = null,
                                  $keyword = [],
                                  $limit = null)
    {
        // Begin constructing the SQL statement.
        $query = "SELECT ";

        // Add the columns to the statement.
        if (is_array($columnName)) {
            // Add each column if columnName is array.
            foreach ($columnName as $column) {
                $query .= $column . ",";
            }
            $query = trim($query, ",");
        } else {
            // Or just add the column.
            $query .= $columnName;
        }

        // Add the table name.
        $query .= " FROM " . $from;

        // Add the where clause.
        if ($where != null) {
            $query .= " WHERE " . $where . " " . $operator . " ?";
        }

        // Add LIMIT clause.
        if ($limit != null) {
            $query .= " LIMIT " . $limit;
        }

        // Finish up the statement.
        $query .= ";";

        // Prepare and execute PDO SQL query.
        $stmt = null;
        try {
            // Prepare PDO statement.
            $stmt = self::getInstance()->prepare($query);

            // Execute statement.
            $stmt->execute($keyword);

            // Write statement to file.
            Functions::getInstance()->writeToLog(SQL_LOG_PATH, $stmt->queryString);
        } catch (PDOException $e) {
            Functions::getInstance()->writeToLog(ERROR_LOG_PATH, $e->getMessage());
            Functions::getInstance()->renderErrorPage(GENERIC_ERROR);
        }

        return $stmt;
    }

    /**
     * Run an INSERT query with PDO.
     *
     * @param $row
     * @param $table
     * @return null|PDOStatement
     */
    public static function INSERT($row, $table)
    {
        // Construct the statement.
        $sql = "INSERT " . "INTO " . $table . " VALUES (NULL,";

        // Add the parts of the record.
        foreach ($row as $value) {
            $sql .= "?" . ",";
        }

        // Trim the right comma.
        $sql = rtrim($sql, ',');

        // Add the final bracket.
        $sql .= ");";

        // Prepare and execute SQL query.
        $stmt = null;
        try {
            // Prepare statement.
            $stmt = self::getInstance()->prepare($sql);

            // Execute statement.
            $stmt->execute($row);

            // Write statement to file.
            Functions::getInstance()->writeToLog(SQL_LOG_PATH, $stmt->queryString);
        } catch (PDOException $e) {
            Functions::getInstance()->writeToLog(ERROR_LOG_PATH, $e->getMessage());
            Functions::getInstance()->renderErrorPage(GENERIC_ERROR);
        }

        return $stmt;
    }

    /**
     * Run SQL DELETE query with PDO.
     *
     * @param $id
     * @param $value
     * @param $table
     * @return null|PDOStatement
     */
    public static function DELETE($id, $value, $table)
    {
        // Construct the statement.
        $sql = "DELETE " . "FROM " . $table . " WHERE " . $id . " = " . "?";

        // Prepare and execute SQL query.
        $stmt = null;
        try {
            // Prepare statement.
            $stmt = self::getInstance()->prepare($sql);

            // Execute the statement.
            $stmt->execute([$value]);

            // Write statement to file.
            Functions::getInstance()->writeToLog(SQL_LOG_PATH, $stmt->queryString);
        } catch (PDOException $e) {
            Functions::getInstance()->writeToLog(ERROR_LOG_PATH, $e->getMessage());
            Functions::getInstance()->renderErrorPage(GENERIC_ERROR);
        }

        return $stmt;
    }
}