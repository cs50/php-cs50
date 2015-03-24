VERSION=4

all:
	tar -czf library50-php_$(VERSION).orig.tar.gz library50-php-$(VERSION)
	cd library50-php-$(VERSION) && debuild -us -uc

host:
	sudo apt-get install devscripts debhelper
