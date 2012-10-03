<?php

/**
 * SMART Monitor config view.
 *
 * @category   Apps
 * @package    Smart_monitor
 * @subpackage Views
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
$this->load->library('smart_monitor/Smart_Monitor');

///////////////////////////////////////////////////////////////////////////////
// Headers 
///////////////////////////////////////////////////////////////////////////////

$headers = array(
    lang('smart_drive'),
    lang('smart_model'),
    lang('smart_size'),
    lang('smart_smartsupport'), 
    lang('smart_health'),
    lang('smart_shorttest')
);

///////////////////////////////////////////////////////////////////////////////
// Row Data
///////////////////////////////////////////////////////////////////////////////

$drives = $this->smart_monitor->get_drives();

foreach ($drives as $drive){
    //find out drive information
    $check = $this->smart_monitor->get_drive_info($drive);

    if($check['available']){
        $available = lang('smart_available');
    } else {
        $available = lang('smart_notavailable');
    }
    if($check['available'] && !$check['enabled']){
        $state = ($check['enabled']) ? 'disable' : 'enable';
        $state_anchor = 'anchor_' . $state;
        $item['anchors'] = $state_anchor('/app/smart_monitor/' . $state . '/' . $drive, 'high');
        $test = "N/A";
        $assessment = "N/A";
    } elseif($check['available'] && $check['enabled']) {
        $state = ($check['enabled']) ? 'disable' : 'enable';
        $state_anchor = 'anchor_' . $state;
        $item['anchors'] = $state_anchor('/app/smart_monitor/' . $state . '/' . $drive, 'high');
        //determine test status
        $teststatus = $this->smart_monitor->get_test_status($drive);
        if($teststatus['running']){
            $test = $teststatus['status']; //"In Progress";
        } else {				
            $test = anchor_custom('/app/smart_monitor/start_test/' . $drive, 'Start');
        }
        $assessment = $this->smart_monitor->get_health($drive);
			
    } else {
        $item['anchors'] = "N/A";
        $test = "N/A";
        $assessment = "N/A";
    }
		
    //populate remaining data
    $item['details'] = array(
        'drive' => $drive . "<input type='hidden' name='drive' value=$drive>",
        'model' => "<span title='".$check['serial']."'>".$check['device'],
        'capacity' => $check['capacity'],
        'available' => $available,
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

