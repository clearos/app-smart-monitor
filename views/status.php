<?php

/**
 * SMART Monitor overview.
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
    lang('smart_temp'),
    lang('smart_powercycle'),
    lang('smart_poweronhours'), 
    //lang('smart_rawreaderror'),
    //lang('smart_seekerror'),
    //lang('smart_reallocsector'),
    lang('smart_spinuptime'),
    //lang('smart_spinretrycnt')
);
$graphheaders = array(
    lang('smart_drive'),
    lang('smart_temp'),
    lang('smart_rawreaderror'),
    lang('smart_seekerror'),
    lang('smart_reallocsector'),
    lang('smart_spinuptime'),
    lang('smart_spinretrycnt')
);
$logheaders = array(
    lang('smart_num'),
    lang('smart_test_description'),
    lang('smart_status'),
    lang('smart_remaining'),
    lang('smart_lifetime'),
    lang('smart_LBA_of_first_error')
);

$keys = array(
    "Temp",
    "PowerCycle",
    "PowerOnHours",
    "RawReadErrorRate",
    "SeekErrorRate",
    "ReAllocSector",
    "SpinUpTime",
    "SpinRetryCnt"
);
$tablekeys = array(
    "Temp",
    "PowerCycle",
    "PowerOnHours",
    "SpinUpTime"
);
$graphkeys = array(
    "Temp",
    "RawReadErrorRate",
    "SeekErrorRate",
    "ReAllocSector",
    "SpinUpTime",
    "SpinRetryCnt"
);


///////////////////////////////////////////////////////////////////////////////
// Row Data
///////////////////////////////////////////////////////////////////////////////

//get all drives list
$drives = $this->smart_monitor->get_drives();

// display drive stats
foreach ($drives as $drive){

    $check = $this->smart_monitor->get_drive_info($drive);

    //only display those with SMART enabled
    if($check['enabled']){
        $data['drive'] = $drive;
        $gdata['drive'] = $drive;
        $output = $this->smart_monitor->get_smart_data($drive);
        $drivename = ltrim($drive,'/dev/');

	//generate raw data
        foreach ($keys as $item){
            $values = array(
                "T" => $output[$item]['T'],
                "W" => $output[$item]['W'],
                "V" => $output[$item]['V']
            );
        
            //generate graphs on the fly, for now use all keys
            try {
                $this->smart_monitor->draw_graph($values,$item,$drivename);
            }  catch (Exception $e) {
                $this->page->view_exception($e);
            }
        }
        //populate table values
        foreach ($tablekeys as $item){
            $data[$item] = $output[$item]['Raw'];
        }
        //populate graph entries
        foreach ($graphkeys as $item){
            $gdata[$item] = "<img src='/approot/smart_monitor/htdocs/graphs/graph_" . $drivename ."_". $item . ".png' />";
        }
        $row['details'] = $data;
        $row['anchors'] = anchor_custom('/app/smart_monitor/attributes/index' . $drive, lang('smart_details'));
        $graphrow['details'] = $gdata;
        
        //append to array for table
        $rows[] = $row;
        $graphrows[] = $graphrow;

        //drive logs
        $log = $this->smart_monitor->get_drive_log($drive);
        $table[$drive] = $log;
    }
}



///////////////////////////////////////////////////////////////////////////////
// Sumary tables
///////////////////////////////////////////////////////////////////////////////
if ($rows != NULL) {
    echo summary_table(
        lang('smart_status_title'),
        NULL,
        $headers,
        $rows
    );
}
if ($rows != NULL) {
    echo summary_table(
        lang('smart_status_charts'),
        NULL,
        $graphheaders,
        $graphrows
    );
}

foreach ($table as $key => $value){
    echo summary_table(
        lang('smart_logs_title') . ' ' . $key,
        NULL,
        $logheaders,
        $value
    );
}



