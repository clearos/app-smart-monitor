<?php

/**
 * SMART Drive log overview.
 *
 * @category   apps
 * @package    smart-monitor
 * @subpackage views
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

$logheaders = array(
    lang('smart_num'),
    lang('smart_test_description'),
    lang('smart_status'),
    lang('smart_remaining'),
    lang('smart_lifetime'),
    lang('smart_LBA_of_first_error')
);

$anchors = array(
    anchor_custom('/app/smart_monitor/', 'Back')
);

$options['default_rows'] = 50;
$options['sort'] = FALSE;
$options['no_action'] = TRUE;
   
// summary table
echo summary_table(
    lang('smart_logs_title') . ' ' . $drive,
    $anchors,
    $logheaders,
    $details
); 
