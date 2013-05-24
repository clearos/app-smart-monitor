
Name: app-smart-monitor
Epoch: 1
Version: 1.1.3
Release: 1%{dist}
Summary: SMART Monitor
License: GPLv3
Group: ClearOS/Apps
Packager: Tim Burgess
Vendor: Tim Burgess
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base

%description
SMART Monitor provides an overview of the self-monitoring, analysis and reporting technology (SMART) system built into many ATA-3 and layer ATA, IDE and SCSI-3 hard drives. The purpose of SMART is to monitor the reliability of the hard drive and predict drive failures, and to carry out different types of drive self-tests.

%package core
Summary: SMART Monitor - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: smartmontools
Requires: webconfig-php-gd

%description core
SMART Monitor provides an overview of the self-monitoring, analysis and reporting technology (SMART) system built into many ATA-3 and layer ATA, IDE and SCSI-3 hard drives. The purpose of SMART is to monitor the reliability of the hard drive and predict drive failures, and to carry out different types of drive self-tests.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/smart_monitor
cp -r * %{buildroot}/usr/clearos/apps/smart_monitor/

install -d -m 755 %{buildroot}/var/clearos/smart_monitor
install -D -m 0644 packaging/smartd.php %{buildroot}/var/clearos/base/daemon/smartd.php
ln -s /var/clearos/smart_monitor %{buildroot}/usr/clearos/apps/smart_monitor/htdocs/graphs

%post
logger -p local6.notice -t installer 'app-smart-monitor - installing'

%post core
logger -p local6.notice -t installer 'app-smart-monitor-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/smart_monitor/deploy/install ] && /usr/clearos/apps/smart_monitor/deploy/install
fi

[ -x /usr/clearos/apps/smart_monitor/deploy/upgrade ] && /usr/clearos/apps/smart_monitor/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-smart-monitor - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-smart-monitor-core - uninstalling'
    [ -x /usr/clearos/apps/smart_monitor/deploy/uninstall ] && /usr/clearos/apps/smart_monitor/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/smart_monitor/controllers
/usr/clearos/apps/smart_monitor/htdocs
/usr/clearos/apps/smart_monitor/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/smart_monitor/packaging
%exclude /usr/clearos/apps/smart_monitor/tests
%dir /usr/clearos/apps/smart_monitor
%dir %attr(755,webconfig,webconfig) /var/clearos/smart_monitor
/usr/clearos/apps/smart_monitor/deploy
/usr/clearos/apps/smart_monitor/language
/usr/clearos/apps/smart_monitor/libraries
/var/clearos/base/daemon/smartd.php
