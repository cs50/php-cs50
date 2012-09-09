############################################################################
Summary: The CS50 Library for PHP simplifies use of CS50 ID.
Name: library50-php
Version: 2
Release: 0
License: BSD 3-Clause License
Group: Applications/Productivity
Source: %{name}-%{version}.zip
Vendor: CS50
URL: https://manual.cs50.net/Library
BuildRoot: %{_tmppath}/%{name}-%{version}-%{release}-root
Requires: openssl, php >= 5.0.0, php-curl, php-gmp, php-xml
BuildArch: noarch


############################################################################
# ensure RPM is portable by avoiding rpmlib(FileDigests)
# http://lists.rpm.org/pipermail/rpm-list/2009-October/000401.html
%global _binary_filedigest_algorithm 1
%global _source_filedigest_algorithm 1


############################################################################
# ensure RPM is portable by avoiding rpmlib(PayloadIsXz)
# http://www.cmake.org/pipermail/cmake/2010-March/035580.html
%global _binary_payload w9.gzdio


############################################################################
%description
The CS50 Library for PHP simplifies use of CS50 ID.


############################################################################
%prep
/bin/rm -rf %{_builddir}/%{name}-%{version}/
/usr/bin/unzip %{_sourcedir}/%{name}-%{version}.zip -d %{_builddir}/


############################################################################
%install
/bin/rm -rf %{buildroot}
/bin/mkdir -p %{buildroot}/usr/share/php
/bin/cp -a %{_builddir}/%{name}-%{version}/CS50 %{buildroot}/usr/share/php/


############################################################################
%clean
/bin/rm -rf %{buildroot}


############################################################################
%files
%defattr(-,root,root,-)
/usr/share/php/


############################################################################
%changelog
* Sun Sep 9 2011 David J. Malan <malan@harvard.edu> - 
- Initial build
