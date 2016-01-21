<?php

    namespace CS50\Database;

    /**
     * CS50's PDO wrapper for MySQL.
     */
    class MySQL
    {
        public function __construct($host, $port, $dbname, $username, $password)
        {
            parent::__construct("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
        }
    }

?>
