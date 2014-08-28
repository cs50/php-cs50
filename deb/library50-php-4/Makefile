prefix = /usr/local
phpdir = $(prefix)/share/php

all:

clean:

install: all
	cp -r CS50/ $(DESTDIR)$(phpdir)
	find $(DESTDIR)$(phpdir) -type d -print0 | xargs -0 chmod 755
	find $(DESTDIR)$(phpdir) -type f -print0 | xargs -0 chmod 644
