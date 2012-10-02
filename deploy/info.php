<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'smart_monitor';
$app['version'] = '1.0.1';
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
$app['subcategory'] = 'Storage';

/////////////////////////////////////////////////////////////////////////////
// Controllers
/////////////////////////////////////////////////////////////////////////////

$app['controllers']['smart']['title'] = lang('smart_monitor_appname');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['core_requires'] = array(
    'smartmontools'
);

$app['core_file_manifest'] = array(
   'smartd.php' => array('target' => '/var/clearos/base/daemon/smartd.php')
);

$app['core_directory_manifest'] = array(
   '/var/clearos/smart_monitor' => array('mode' => '755', 'owner' => 'webconfig', 'group' => 'webconfig')
);
