<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'smart_monitor';
$app['version'] = '1.0.3';
$app['release'] = '1';
$app['vendor'] = 'Tim Burgess';
$app['packager'] = 'Tim Burgess';
$app['license'] = 'GPLv3';
$app['license_core'] = 'LGPLv3';
$app['description'] = lang('smart_monitor_app_description');
$app['tooltip'] = lang('smart_tooltip');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('smart_monitor_appname');
$app['category'] = lang('base_category_system');
$app['subcategory'] = lang('base_subcategory_storage');

/////////////////////////////////////////////////////////////////////////////
// Controllers
/////////////////////////////////////////////////////////////////////////////

$app['controllers']['smart']['title'] = lang('smart_monitor_appname');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['core_requires'] = array(
    'smartmontools',
    'webconfig-php-gd'
);

$app['core_file_manifest'] = array(
   'smartd.php' => array('target' => '/var/clearos/base/daemon/smartd.php')
);

$app['core_directory_manifest'] = array(
   '/var/clearos/smart_monitor' => array('mode' => '755', 'owner' => 'webconfig', 'group' => 'webconfig')
);

$app['htdocs_symlinks'] = array(
    '/var/clearos/smart_monitor' => '/usr/clearos/apps/smart_monitor/htdocs/graphs',
);

$app['delete_dependency'] = array(
    'app-smart-monitor-core',
    'smartmontools'
);
