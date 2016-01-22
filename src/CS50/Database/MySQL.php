<?php

    namespace CS50\Database;

    /**
     * Wrapper for PDO's MySQL driver.
     */
    class MySQL extends \CS50\Database
    {
        public function __construct($host, $port, $dbname, $username, $password)
        {
            parent::__construct("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
        }
    }

?>
