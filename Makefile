DESTDIR ?= /usr/local/share/php
VERSION = 6.0.0

.PHONY: build
build: clean
	mkdir -p build/usr/share/php/php-cs50
	cp -r src/* build/usr/share/php/php-cs50

.PHONY: clean
clean:
	rm -rf build php-cs50* php-cs50_*

.PHONY: deb
deb: build
	rm -rf build/deb &>/dev/null
	@echo "php-cs50 ($(VERSION)-0ubuntu$(DIST_VERSION)) $(DIST); urgency=low" > debian/changelog
	@echo "  * v$(VERSION)" >> debian/changelog
	@echo " -- CS50 Sysadmins <sysadmins@cs50.harvard.edu>  $$(date --rfc-2822)" >> debian/changelog
	mkdir -p php-cs50-$(VERSION)
	cp -r build/usr php-cs50-$(VERSION)
	GZIP=-n tar --mtime='1970-01-01' -cvzf php-cs50_$(VERSION).orig.tar.gz php-cs50-$(VERSION)
	cp -r debian php-cs50-$(VERSION)
	cd php-cs50-$(VERSION) && debuild $(SIGNING_OPTS) -S -sa --lintian-opts --display-info --info --show-overrides
	mkdir -p build/deb
	mv php-cs50* build/deb

.PHONY: install
install: build
	mkdir -p $(DESTDIR)
	cp -r build/usr/share/php/php-cs50/* $(DESTDIR)

.PHONY: version
version:
	@echo $(VERSION)
