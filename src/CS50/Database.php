<?php

    namespace CS50;

    /**
     * Wrapper for PDO that only exposes a student-friendly query function.
     */
    abstract class Database
    {
        /**
         * PDO instance.
         */
        protected $handle;

        /**
         * Creates a PDO instance to represent a connection to the requested database.
         */
        public function __construct($dsn, $username, $password)
        {
            try
            {
                $this->handle = new PDO($dsn, $username, $password);
            }
            catch (Exception $e)
            {
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }

        /**
         * Executes SQL statement after substituting (escaped) values for positional placeholders, if any.
         * If query is SELECT, returns array of rows; if query is DELETE, INSERT, or UPDATE,
         * returns number of rows affected.
         *
         * @param string $sql
         * @param mixed [parameter ...]
         *
         * @return array|number
         */
        public function query(/* $sql [, ... ] */)
        {
            // SQL statement
            $sql = func_get_arg(0);

            // parameters, if any
            $parameters = array_slice(func_get_args(), 1);

            // ensure number of placeholders matches number of values
            // http://stackoverflow.com/a/22273749
            // https://eval.in/116177
            $pattern = "
                /(?:
                '[^'\\\\]*(?:(?:\\\\.|'')[^'\\\\]*)*'
                | \"[^\"\\\\]*(?:(?:\\\\.|\"\")[^\"\\\\]*)*\"
                | `[^`\\\\]*(?:(?:\\\\.|``)[^`\\\\]*)*`
                )(*SKIP)(*F)| \?
                /x
            ";
            preg_match_all($pattern, $sql, $matches);
            if (count($matches[0]) < count($parameters))
            {
                trigger_error("Too few placeholders in query", E_USER_ERROR);
            }
            else if (count($matches[0]) > count($parameters))
            {
                trigger_error("Too many placeholders in query", E_USER_ERROR);
            }

            // replace placeholders with quoted, escaped strings
            $patterns = [];
            $replacements = [];
            for ($i = 0, $n = count($parameters); $i < $n; $i++)
            {
                array_push($patterns, $pattern);
                array_push($replacements, preg_quote($handle->quote($parameters[$i])));
            }
            $query = preg_replace($patterns, $replacements, $sql, 1);

            // execute query
            $statement = $handle->query($query);
            if ($statement === false)
            {
                trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            }
   
            // if query was SELECT
            // http://stackoverflow.com/a/19794473/5156190
            if ($statement->columnCount() > 0)
            {
                // return result set's rows
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }

            // if query was DELETE, INSERT, or UPDATE
            else
            {
                // return number of rows affected
                return $statement->rowCount();
            }
        }
    }

?>
