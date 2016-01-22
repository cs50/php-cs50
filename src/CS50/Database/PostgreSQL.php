<?php

    namespace CS50\Database;

    /**
     * Wrapper for PDO's PostgreSQL driver.
     */
    class PostgreSQL extends \CS50\Database
    {
        public function __construct($host, $port, $dbname, $user, $password)
        {
            parent::__construct("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        }
    }

?>
