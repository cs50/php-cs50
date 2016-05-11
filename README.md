# CS50 Library for PHP

## Development

Requires [Docker Toolbox](https://www.docker.com/products/docker-toolbox).

    docker-compose run cli # runs CS50 CLI

## Usage

    // assumes CS50.php is in include_path
    require("CS50.php");

    ...

    $c = CS50\get_char();
    $f = CS50\get_float();
    $i = CS50\get_int();
    $s = CS50\get_string();

## TODO

* Decide whether to add `CS50.eprintf`.
* Add support for `composer`.
* Add tests.
* Review `ID.php`, `Database.php`, etc.
