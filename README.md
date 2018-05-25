# CS50 Library for PHP

## Development

Requires [Docker Toolbox](https://www.docker.com/products/docker-toolbox).

    docker-compose run cli # runs CS50 CLI

## Installation

### Ubuntu:

```
$ curl -s https://packagecloud.io/install/repositories/cs50/repo/script.deb.sh | sudo bash
$ sudo apt-get install php-cs50
```

### Fedora

```
$ curl -s https://packagecloud.io/install/repositories/cs50/repo/script.rpm.sh | sudo bash
$ sudo yum install php-cs50
```

### From Source

1. Download the latest release from https://github.com/cs50/php-cs50/releases
1. Extract `php-cs50*`
1. cd `php-cs50`
1. `make install # may require sudo`

By default, we install to `/usr/local/share/php`. If you'd like to change the installation location, run `DESTDIR=/path/to/install make install` as desired.

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
* Add tests.
* Review `ID.php`, `Database.php`, etc.
