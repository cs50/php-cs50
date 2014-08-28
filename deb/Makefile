VERSION=4

all:
	mv library50-php-$(VERSION)/debian .
	tar -czf library50-php_$(VERSION).orig.tar.gz library50-php-$(VERSION)
	mv debian library50-php-$(VERSION)
	cd library50-php-$(VERSION) && debuild -us -uc
