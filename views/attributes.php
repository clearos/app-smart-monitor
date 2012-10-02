<?php

/**
 * SMART Monitor Attribute overview.
 *
 * @category   Apps
 * @package    smart_monitor
 * @subpackage Views
 * @author     Tim Burgess <trburgess@gmail.com>
 * @copyright  2012 ClearFoundation
 * @copyright  Flot JS Chart 2007-2009 IOLA and Ole Laursen
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later. 
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


// load language
$this->lang->load('smart_monitor');

$headers = array(
    lang('smart_attributes_id'),
    lang('smart_attributes_name'),
    lang('smart_attributes_value'),
    lang('smart_attributes_worst'),
    lang('smart_attributes_thresh'),
    lang('smart_attributes_type'),
    lang('smart_attributes_updated'),
    lang('smart_attributes_when_failed'),
    lang('smart_attributes_raw')
);

$anchors = array(
    anchor_custom('/app/smart_monitor/', 'Back')
);

//echo json_encode($attributes);

// reassemble array for table summary
foreach ($attributes as $id => $entry){
    if ($id > 6){
        $row['title'] = $id;
        //$row['action'] = NULL;
        //$row['anchors'] = NULL;
        $entry = " ".$entry;
        $entry = preg_replace('/\s+/m',"|",$entry);
        $values = explode("|",$entry);
        
        $row['details'] = array( 
            $values[1],$values[2],$values[4],$values[5],$values[6],$values[7],$values[8],$values[9],$values[10]
        );
        $rows[] = $row;
     }
}

//echo json_encode($rows);

$options['default_rows'] = 50;
$options['sort'] = FALSE;
$options['no_action'] = TRUE;
   
// summary table
echo summary_table(
    lang('smart_attributes_title') . ' ' . $drive,
    $anchors,
    $headers,
    $rows,
    $options
);
 
