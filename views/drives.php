<?php

/**
 * SMART Monitor config view.
 *
 * @category   apps
 * @package    smart-monitor
 * @subpackage views
 * @author     Tim Burgess <trburgess@gmail.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearcenter.com/support/documentation/clearos/smart_monitor/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.  
//  
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('smart_monitor');
$this->lang->load('base');

///////////////////////////////////////////////////////////////////////////////
// Headers 
///////////////////////////////////////////////////////////////////////////////

$headers = array(
    lang('smart_drive'),
    lang('smart_model'),
    lang('smart_size'),
    lang('smart_health'),
    lang('smart_shorttest')
);

///////////////////////////////////////////////////////////////////////////////
// Row Data
///////////////////////////////////////////////////////////////////////////////

foreach ($drives as $drive) {
    $name = $drive['name'];

    $available = FALSE;
    if ($drive['info']['available'])
        $available = TRUE;

    if ($drive['info']['available'] && !$drive['info']['enabled']) {
        $state = ($drive['info']['enabled']) ? 'disable' : 'enable';
        $state_anchor = 'anchor_' . $state;
        $item['anchors'] = $state_anchor('/app/smart_monitor/' . $state . '/' . $name, 'high');
        $test = "";
        $assessment = "---";
    } elseif ($drive['info']['available'] && $drive['info']['enabled']) {
        $state = ($drive['info']['enabled']) ? 'disable' : 'enable';
        $state_anchor = 'anchor_' . $state;
        $item['anchors'] = $state_anchor('/app/smart_monitor/' . $state . '/' . $name, 'high');
        if ($drive['teststatus']['running']) {
            $test = $drive['teststatus']['status']; //"In Progress";
        } else {
            $test = anchor_custom('/app/smart_monitor/start_test/' . $name, lang('base_start'));
        } 
        $assessment = $drive['assessment'];
    } else {
        $item['anchors'] = "";
        $test = "---";
        $assessment = "---";
    }
    if (!$available)
        $test = lang('smart_unsupported');

    //populate remaining data

    $item['details'] = array(
        'drive' => $name . "<input type='hidden' name='drive' value='$name'>",
        'model' => "<span data-toggle='tooltip' data-container='body' title='" . $drive['info']['serial'] . "'>" . $drive['info']['device'],
        'capacity' => $drive['info']['capacity'],
        'health' => $assessment,
        'test' => $test
    );

    $items[] = $item;
}

///////////////////////////////////////////////////////////////////////////////
// Sumary table
///////////////////////////////////////////////////////////////////////////////

echo form_open('smart_monitor/drives');
echo summary_table(
    lang('smart_config_title'),
    NULL,
    $headers,
    $items
);
echo form_close();

