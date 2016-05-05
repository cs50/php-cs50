<?php

    namespace CS50;

    /**
     *
     */
    function get_char()
    {
        while (true)
        {
            $s = readline();
            if ($s === false)
            {
                return CHAR_MAX;
            }
            if (strlen($s) === 1)
            {
                return $s[0];
            }
            print("Retry: ");
        }
    }

    /**
     *
     */
    function get_float()
    {
        while (true)
        {
            $s = readline();
            if ($s === false)
            {
                return INF;
            }
            if (preg_match("/^(\+|-)?\d+(\.\d*)?$/", $s))
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
     *
     */
    function get_int()
    {
        while (true)
        {
            $s = readline();
            if ($s === false)
            {
                return PHP_INT_MAX;
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
     *
     */
    function get_string()
    {
        return readline();
    }

?>
