<?php

    namespace CS50\Database;

    /**
     * CS50's PDO wrapper for PostgreSQL.
     */
    class PostgreSQL
    {
        public function __construct($host, $port, $dbname, $user, $password)
        {
            parent::__construct("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
        }
    }

?>
