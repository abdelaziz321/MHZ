<?php

class DB
{
    private static $_instance;
    private $_pdo;

    /**
     * filled with results when using select method
     *
     * @var stdClass
     */
    private $_results;

    /**
     * counting numeber of row
     *
     * @var int
     */
    private $_count = 0;

    /**
     * determine if there are any errors
     *
     * @var bool
     */
    private $_errors = false;


    /**
     * connect to the mysql database using PDO class
     */
    private function __construct()
    {
        try {
            $options = [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            extract($GLOBALS['_config']);
            $this->_pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password, $options);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * using singleton pattern to get only instance of the databse class
     *
     * @return DB the DB instance
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        } else {
            # reset the previous instance's attribute
            self::$_instance->_results = null;
            self::$_instance->_count = 0;
            self::$_instance->_errors = false;
        }
        return self::$_instance;
    }

    /**
     * execute the query and fill the instance's attribute
     *
     * @param  string $sql
     * @param  array  $params
     * @return bool   determine if any error occure wile executing the query
     */
    private function query($sql, $params = [])
    {
        if ($statement = $this->_pdo->prepare($sql)) {
            if ($statement->execute($params)) {
                if (strpos($sql, 'SELECT') === 0) {
                    $this->_results = $statement->fetchAll();
                }
                $this->_count = $statement->rowCount();
            } else {
                $this->_errors = true;
            }
        } else {
            $this->_errors = true;
        }
        return $this->_errors;
    }

    /**
     * Build the insert query
     *
     * @param  string $sql    Ex: 'INTO users SET name=:name'
     * @param  array  $params Ex: [':name' => $name]
     * @return mixed  false if any error occure wile executing the query,
     *                if not return the number of the affected rows
     */
    public function insert($sql, $params = [])
    {
        $sql = 'INSERT ' . $sql;
        if (!$this->query($sql, $params)) {
            return $this->_count;
        }
        return false;
    }

    /**
     * Build the insert query
     *
     * @param  string $sql    Ex: 'INTO users SET name=:name'
     * @param  array  $params Ex: [':name' => $name]
     * @return mixed  false if any error occure wile executing the query,
     *                if not return the number of the affected rows
     */
    public function update($sql, $params = [])
    {
        $sql = 'UPDATE ' . $sql;
        if (!$this->query($sql, $params)) {
            return $this->_count;
        }
        return false;
    }

    /**
     * Build the delete query
     *
     * @param  string $sql    Ex: 'FROM users WHERE id = :id'
     * @param  array  $params Ex: [':id' => $id]
     * @return mixed  false if any error occure wile executing the query,
     *                if not return the number of the affected rows
     */
    public function delete($sql, $params = [])
    {
        $sql = 'DELETE ' . $sql;
        if (!$this->query($sql, $params)) {
            return $this->_count;
        }
        return false;
    }

    /**
     * Build the select query
     *
     * @param  string $sql    Ex: 'name FROM users WHERE id = :id'
     * @param  array  $params Ex: [':id' => $id]
     * @return mixed  false if any error occure wile executing the query,
     *                if not return the result
     */
    public function select($sql, $params = [])
    {
        $sql = 'SELECT ' . $sql;
        if (!$this->query($sql, $params)) {
            return $this->_results;
        }
        return false;
    }

    /**
     * return the retrieved rows when using the select mehtod
     *
     * @return stdClass
     */
    public function results()
    {
        return $this->_results;
    }

    /**
     * get the first row of the results if exists, otherwise return false.
     *
     * @return mixed
     */
    public function first()
    {
        if (empty($this->_results)) {
            return false;
        }
        return $this->_results[0];
    }

    /**
     * get the total number of the retrieved rows
     *
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }

    /**
     * determine if any error happend while executing the query
     *
     * @return bool
     */
    public function errors()
    {
        return $this->_errors;
    }
}


/*================================
            How To Use
**================================
$instance = DB::getInstance();
$instance->select('* FROM accounts WHERE id < :id', [
    ':id' => 5
]);

if(!$instance->errors()) {
    var_dump($instance->count());
    var_dump($instance->first());
    var_dump($instance->results());
}
================================*/
