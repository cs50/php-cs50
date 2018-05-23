DESTDIR ?= /usr/local/share/php
VERSION = 6.0.0

SRC := $(wildcard src/**/*.php)

.PHONY: clean
clean:
	rm -rf build

.PHONY: deb
deb: $(SRC)
	rm -rf build/deb
	mkdir -p build/deb/php-cs50/usr/share/php
	cp -r src/* build/deb/php-cs50/usr/share/php

	fpm \
	--category php \
	--conflicts library50-php \
	--chdir build/deb/php-cs50 \
	--deb-priority optional \
	--description "CS50 library for PHP" \
	--input-type dir \
	--license "" \
	--maintainer "CS50 <sysadmins@cs50.harvard.edu>" \
	--name php-cs50 \
	--output-type deb \
	--package build/deb \
	--provides library50-php \
	--provides php-cs50 \
	--replaces library50-php \
	--replaces php-cs50 \
	--url https://github.com/cs50/php-cs50 \
	--vendor CS50 \
	--version $(VERSION) \
	.

	rm -rf build/deb/php-cs50

.PHONY: install
install:
	mkdir -p $(DESTDIR)
	cp -r src/* $(DESTDIR)

.PHONY: version
version:
	@echo $(VERSION)
