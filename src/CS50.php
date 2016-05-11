<?php

    namespace CS50;

    /**
     * Reads a line of text from standard input and returns the equivalent
     * character (as a string of length 1); if text does not represent a character,
     * user is prompted to retry. If line can't be read, returns false.
     */
    function get_char()
    {
        while (true)
        {
            $s = readline();
            if ($s === false)
            {
                return false;
            }
            if (strlen($s) === 1)
            {
                return $s[0];
            }
            print("Retry: ");
        }
    }

    /**
     * Reads a line of text from standard input and returns the equivalent
     * float as precisely as possible; if text does not represent a
     * float or if value would cause underflow or overflow, user is
     * prompted to retry. If line can't be read, returns false.
     */
    function get_float()
    {
        while (true)
        {
            $s = readline();
            if ($s === false)
            {
                return false;
            }
            if (preg_match("/^(\+|-)?\d*(\.\d*)?$/", $s))
            {
                $f = floatval($s);
                if ($f !== INF)
                {
                    return $f;
                }
            }
            print("Retry: ");
        }
    }

    /**
     * Reads a line of text from standard input and returns it as an
     * integer in [-2^31, 2^31 - 1) if possible; if text does not represent
     * such an integer or if value would cause underflow or overflow,
     * user is prompted to retry. If line can't be read, returns false.
     */
    function get_int()
    {
        while (true)
        {
            $s = readline();
            if ($s === false)
            {
                return false;
            }
            if (preg_match("/^(\+|-)?\d+$/", $s))
            {
                $n = intval($s);
                if ($n !== PHP_INT_MAX)
                {
                    return $n;
                }
            }
            print("Retry: ");
        }
    }

    /**
     * Reads a line of text from standard input and returns it as a
     * string, sans trailing newline character. (Ergo, if user inputs
     * only "\n", returns "" not null.) Returns false upon error or no
     * input whatsoever (i.e., just EOF).
     */
    function get_string()
    {
        return readline();
    }

?>
